<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PurgeTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-test-data
                            {--force : Skip the confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove partners, form submissions and queue leftovers created during testing, keeping admins and catalogs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $counts = $this->countPending();

        if (array_sum($counts) === 0) {
            $this->info('Nothing to purge.');

            return self::SUCCESS;
        }

        $this->table(['Table', 'Rows'], $this->rows($counts));

        if (! $this->option('force') && ! $this->confirm('Delete all of the above? This cannot be undone.')) {
            $this->info('Aborted. No data was changed.');

            return self::SUCCESS;
        }

        $purged = DB::transaction(fn () => $this->purge());

        $this->info('Test data purged.');
        $this->table(['Table', 'Rows'], $this->rows($purged));

        return self::SUCCESS;
    }

    /**
     * Counts the rows each purge step would touch
     */
    protected function countPending(): array
    {
        return [
            'form_submission_notifications' => DB::table('form_submission_notifications')->count(),
            'form_responses' => DB::table('form_responses')->count(),
            'form_submissions' => DB::table('form_submissions')->count(),
            'localities (detached, kept)' => DB::table('localities')->whereIn('user_id', $this->partnerIds())->count(),
            'sessions' => $this->partnerSessions()->count(),
            'password_reset_tokens' => $this->partnerResetTokens()->count(),
            'personal_access_tokens' => $this->partnerAccessTokens()->count(),
            'users (partners)' => $this->partners()->count(),
            'jobs' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
        ];
    }

    /**
     * Runs every purge step. The query builder bypasses SoftDeletes and model events.
     */
    protected function purge(): array
    {
        return [
            // Notifications have no cascade on form_submission_id, so they go first
            'form_submission_notifications' => DB::table('form_submission_notifications')->delete(),
            'form_responses' => DB::table('form_responses')->delete(),
            'form_submissions' => DB::table('form_submissions')->delete(),
            // localities.user_id cascades on delete: detach before removing partners
            'localities (detached, kept)' => DB::table('localities')
                ->whereIn('user_id', $this->partnerIds())
                ->update(['user_id' => null]),
            'sessions' => $this->partnerSessions()->delete(),
            'password_reset_tokens' => $this->partnerResetTokens()->delete(),
            'personal_access_tokens' => $this->partnerAccessTokens()->delete(),
            'users (partners)' => $this->partners()->delete(),
            'jobs' => DB::table('jobs')->delete(),
            'failed_jobs' => DB::table('failed_jobs')->delete(),
        ];
    }

    protected function partners(): Builder
    {
        return DB::table('users')->where('role', User::PARTNER_USER);
    }

    protected function partnerIds(): Builder
    {
        return $this->partners()->select('id');
    }

    protected function partnerSessions(): Builder
    {
        return DB::table('sessions')->whereIn('user_id', $this->partnerIds());
    }

    protected function partnerResetTokens(): Builder
    {
        return DB::table('password_reset_tokens')->whereIn('email', $this->partners()->select('email'));
    }

    protected function partnerAccessTokens(): Builder
    {
        return DB::table('personal_access_tokens')
            ->where('tokenable_type', (new User)->getMorphClass())
            ->whereIn('tokenable_id', $this->partnerIds());
    }

    protected function rows(array $counts): array
    {
        return collect($counts)
            ->map(fn (int $count, string $table) => [$table, $count])
            ->values()
            ->all();
    }
}
