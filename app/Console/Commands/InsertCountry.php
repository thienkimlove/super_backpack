<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class InsertCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:country';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {

        $file = fopen(base_path('data/country-codes.csv'), 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            //$line[0] = '1004000018' in first iteration
            Location::create([
                'name' => $line[21],
                'code' => $line[7],
            ]);
        }
        fclose($file);

    }
}
