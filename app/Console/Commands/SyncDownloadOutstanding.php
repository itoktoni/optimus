<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        $curl = Curl::to(env('SYNC_SERVER') . 'sync_outstanding_download')
        ->withData(
            [
                'limit' => env('SYNC_LIMIT', 100),
                'page' => 1,
                'download' => true,
            ]
        )->withHeaders(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        )->withBearer(env('SYNC_TOKEN'))->get();
        
        $outstanding = json_decode($curl, true);

        if(isset($outstanding)){
            // $bulk = array_values($outstanding['data']['data']);
            DB::table('linen_outstanding')->insert($outstanding);
        }

        $this->info('The system has been download successfully!');
    }

}
