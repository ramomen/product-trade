<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running migrations...');
        $this->call('migrate:fresh');
        $this->info('Migrations finished.');

        $this->info('Running database monitor...');
        $this->call('db:monitor');

        $this->info('Running seeds...');
        // run command with class name
        Artisan::call('db:seed --class=ProductSeeder');
        Artisan::call('db:seed --class=OrderSeeder');
        Artisan::call('db:seed --class=OfferSeeder');
        Artisan::call('db:seed --class=UserSeeder');

        $this->info('Seeds finished.');

        dump((object)[
            'message' => 'Test User',
            'email' => 'verde@laravel.com',
            'password' => '123456'
        ]);

        $this->info('Setup finished.');
    }
}
