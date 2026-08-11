<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Str;

/**
 * Visible sender identity (From + Reply-To) of an outgoing message.
 *
 * The provider relaying the message is irrelevant to the recipient: what has to
 * be consistent is who the message is coming from. A requester hears from the
 * partner assigned to their submission; a partner hears from the requester. The
 * system mailbox is only used while there is no human counterpart yet.
 *
 * The From address, however, is not a free-form label: it is what SPF and DKIM
 * are checked against. Sending as an address on a domain we do not control lets
 * that domain's DMARC policy decide our delivery, and large mailbox providers
 * publish p=reject. So the address falls back to the platform mailbox whenever
 * it is not on our own sending domain, while the display name and the Reply-To
 * keep pointing at the real person.
 */
class SenderIdentity
{
    private function __construct(
        public readonly string $address,
        public readonly string $name,
    ) {}

    /**
     * The platform mailbox (MAIL_FROM_ADDRESS / MAIL_FROM_NAME).
     */
    public static function system(): self
    {
        return new self(
            (string) config('mail.from.address'),
            (string) config('mail.from.name'),
        );
    }

    /**
     * The partner assigned to a submission, or the system mailbox while the
     * submission has no partner.
     */
    public static function forPartner(?User $partner): self
    {
        if (! $partner || ! filled($partner->email)) {
            return self::system();
        }

        return new self($partner->email, filled($partner->name) ? $partner->name : $partner->email);
    }

    /**
     * The person who filled the public form.
     *
     * @param  mixed  $data  Decoded FormSubmission payload; comes from a JSON
     *                       column, so it is not guaranteed to be an array.
     */
    public static function forRequester($data): self
    {
        if (! is_array($data)) {
            return self::system();
        }

        $email = $data['email'] ?? null;

        if (! is_string($email) || ! filled($email)) {
            return self::system();
        }

        $name = $data['name'] ?? null;

        return new self($email, is_string($name) && filled($name) ? $name : $email);
    }

    /**
     * From header: the real address only when we are authorised to sign for it.
     *
     * MAIL_FORCE_FROM_ADDRESS additionally pins every message to a single
     * mailbox, for providers that only relay one validated sender.
     */
    public function from(): Address
    {
        $forced = config('mail.force_from_address');

        if (filled($forced)) {
            return new Address((string) $forced, $this->name);
        }

        return new Address(
            $this->isOnSendingDomain() ? $this->address : (string) config('mail.from.address'),
            $this->name,
        );
    }

    /**
     * @return array<int, Address>
     */
    public function replyTo(): array
    {
        return [new Address($this->address, $this->name)];
    }

    /**
     * Whether this address belongs to the domain the application authenticates
     * with, which is the domain of MAIL_FROM_ADDRESS. A partner on the corporate
     * domain sends as itself; anyone else (a requester on gmail, a partner
     * created with an external address) only gets the name and the Reply-To.
     */
    private function isOnSendingDomain(): bool
    {
        $sendingDomain = Str::lower(Str::after((string) config('mail.from.address'), '@'));

        return $sendingDomain !== ''
            && Str::endsWith(Str::lower($this->address), '@'.$sendingDomain);
    }
}
