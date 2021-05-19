<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;
use Modules\Linen\Dao\Facades\OutstandingFacades;
use Modules\Linen\Http\Services\OutstandingMasterService;

class SyncDownloadOutstanding extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:download_outstanding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This commands to download outstanding transaction from server to local';

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
        $curl = Curl::to(env('API_SERVER') . 'linen_outstanding/data')
        ->withData(
            [
                'limit' => config('website.pagination'),
                'page' => 1,
                'download' => true,
            ]
        )->withHeaders(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        )->withBearer(env('API_TOKEN'))->post();
        
        $outstanding = json_decode($curl, true);
        if(isset($outstanding['data']['data'])){
            $bulk = array_values($outstanding['data']['data']);
            OutstandingFacades::insert($bulk);
        }

        $this->info('The system has been download successfully!');
    }

}
