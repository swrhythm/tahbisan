<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('admin:password {password : The new password} {--name=Admin : Admin account name}')]
#[Description('Create or update an admin login for the Tahbisan admin dashboard')]
class AdminPasswordCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name');

        Admin::updateOrCreate(
            ['name' => $name],
            ['password' => $this->argument('password')]
        );

        $this->info("Admin \"{$name}\" password set.");

        return self::SUCCESS;
    }
}
