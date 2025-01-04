<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    public function update($user, array $input)
    {
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255'], // Remplace 'name' par 'username'
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('utilisateurs')->ignore($user->id), // Remplace 'users' par 'utilisateurs' si nécessaire
            ],
            'phone_number' => ['nullable', 'string', 'max:15'], // Validation pour phone_number
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'username' => $input['username'], // Remplace 'name' par 'username'
                'email' => $input['email'],
                'phone_number' => $input['phone_number'], // Ajout de phone_number
            ])->save();
        }
    }

    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([

            'username' => $input['username'], // Mise à jour de "username"
            'email' => $input['email'],
            'email_verified_at' => null,
            'phone_number' => $input['phone_number'], // Ajout de phone_number
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
