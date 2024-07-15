<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new user account as an admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the admin name?');
        $email = $this->ask('What is the admin email?');
        $phone = $this->ask('What is the admin phone?');
        $password = $this->ask('What is the admin password?');

        if(is_null($name) || is_null($email) || is_null($phone) || is_null($password)){
            $this->error('All inputs are required ... Aborting');
            return;
        }
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->password = Hash::make($password);
        $user->is_admin = 1;
        $user->save();
        $this->alert('A new admin account has been created successfully');
    }
}
