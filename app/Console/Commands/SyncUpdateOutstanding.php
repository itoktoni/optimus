<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Modules\Linen\Dao\Facades\OutstandingFacades;
use Modules\Linen\Http\Services\OutstandingMasterService;

class SyncUpdateOutstanding extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:upload_outstanding';

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
        $outstanding = OutstandingFacades::dataRepository()
        ->where('linen_outstanding_status', 2)
        ->where('linen_outstanding_description', '!=', 3)
        ->whereNull('linen_outstanding_uploaded_at')
        ->limit(env('SYNC_LIMIT', 100))
        ->get()
        ->pluck('linen_outstanding_rfid');

        $curl = Curl::to(env('SYNC_SERVER') . 'sync_outstanding_upload')
        ->withData(
            [
                'id' => $outstanding,
            ]
        )->withHeaders(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        )->withBearer(env('SYNC_TOKEN'))->post();

        if(isset($outstanding)){
            DB::table('linen_outstanding')
            ->whereIn('linen_outstanding_rfid', $outstanding)
            ->update([
                'linen_outstanding_uploaded_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->info('The system has been download successfully!');
    }

}
