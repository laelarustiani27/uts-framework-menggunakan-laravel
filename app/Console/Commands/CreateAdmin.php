<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create or promote an administrator account';

    public function handle(): int
    {
        $name = $this->ask('Nama admin');
        $email = $this->ask('Email admin');
        $passwordPrompt = 'Password admin (minimal 12 karakter)';
        $confirmationPrompt = 'Ulangi password admin';

        if (PHP_OS_FAMILY === 'Windows') {
            $this->warn('Input password akan terlihat saat diketik di terminal Windows.');
            $password = $this->ask($passwordPrompt);
            $passwordConfirmation = $this->ask($confirmationPrompt);
        } else {
            $password = $this->secret($passwordPrompt);
            $passwordConfirmation = $this->secret($confirmationPrompt);
        }

        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'confirmed', Password::min(12)],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'is_admin' => true,
            ],
        );

        $this->info("Akun admin {$email} berhasil dibuat atau diperbarui.");

        return self::SUCCESS;
    }
}
