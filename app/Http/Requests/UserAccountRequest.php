<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserAccountRequest extends FormRequest
{
    /**
     * Roles que se pueden gestionar desde /dashboard/users. Los partners
     * tienen su propia sección y no se editan desde acá.
     */
    private const MANAGEABLE_ROLES = [User::ADMIN_USER];

    public function authorize(): bool
    {
        $user = $this->route('user');

        if ($user && $user->role === User::PARTNER_USER) {
            throw new NotFoundHttpException;
        }

        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('delete')) {
            return [];
        }

        $userId = $this->route('user')?->id ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$userId,
            'phone' => 'required|string|max:255',
            'password' => $this->isMethod('post') ? 'required|string|min:8|confirmed' : 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(self::MANAGEABLE_ROLES)],
        ];
    }
}
