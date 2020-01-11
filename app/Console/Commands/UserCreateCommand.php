<?php

namespace Azuriom\Console\Commands;

use Azuriom\Models\Role;
use Azuriom\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                        {--admin= : If the user is admin}
                        {--name= : The name of the user}
                        {--password= : The password of the user}
                        {--email= : The email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [
            'role_id' => 1,
        ];

        $admin = $this->option('admin') ?? $this->confirm('Is the user should be admin ?');

        $data['name'] = $this->option('name') ?? $this->ask('The username of the user');
        $data['email'] = $this->option('email') ?? $this->ask('The Email address of the user');
        $data['password'] = Hash::make($this->secret('The password of the user'));

        if ($admin) {
            $data['role_id'] = Role::where('is_admin', true)->firstOrFail()->id;
        }

        User::create($data);

        $this->info('User created !');
    }
}