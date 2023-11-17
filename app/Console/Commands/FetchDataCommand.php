<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from external API and update database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // fetch data from '/products' endpoint
        $response = Http::get('/products');        
    }
}
