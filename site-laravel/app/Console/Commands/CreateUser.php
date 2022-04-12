<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "ecomag:create-user
                            {username : New user's name}
                            {--e|email= : Optional user's email}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

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
        $username = $this->argument("username");
        $email = $this->option("email") ?: "";
        $password = $this->secret("User's password");

        (new User([
            "name" => $username,
            "password" => Hash::make($password),
            "email" => $email,
        ]))->save();

        $this->info("User $username has been created successfully.");
        return 0;
    }
}
