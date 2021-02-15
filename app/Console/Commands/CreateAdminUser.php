<?php

namespace App\Console\Commands;

use App\Models\User;
use Hash;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name} {email} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user with allowed permission.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!filter_var($email = $this->argument('email'), FILTER_VALIDATE_EMAIL)) {
            $this->error(trans('validation.email', ['attribute' => 'email']));
            return false;
        }

        if (!$password = $this->argument('password')) {
            $this->info('Will be generated random password string.');
            $password = Str::random(User::PASSWORD_LENGTH);
        }

        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => Hash::make($password),
            'is_allowed' => true,
        ]);

        if (!$user) {
            $this->error('User not created. Something wrong');
            return false;
        }

        $this->info("Created admin user name: $user->name, password: $password");
        return true;
    }
}
