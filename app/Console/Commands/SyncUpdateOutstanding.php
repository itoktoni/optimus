<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
    protected $signature = 'sync:update_outstanding';

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
        $outstanding = DB::connection('testing')->table('linen_outstanding')
        ->where('linen_outstanding_status', 2)
        ->where('linen_outstanding_description', '!=', 3)
        ->whereNull('linen_outstanding_uploaded_at')
        ->limit(env('SYNC_LIMIT', 100))
        ->get()
        ->pluck('linen_outstanding_rfid');

        $curl = Http::withToken('245|kibh7d0CHZRmU3AxLaFFtKHKnyQsu4jRbgCebGD7')->withoutVerifying()
        ->withOptions(['debug' => true])
            ->post(env('SYNC_SERVER') . 'sync_outstanding_update', [
                'rfid' => $outstanding,
            ]);

        if(isset($outstanding)){
            DB::connection('testing')->table('linen_outstanding')
            ->whereIn('linen_outstanding_rfid', $outstanding)
            ->update([
                'linen_outstanding_uploaded_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->info('The system has been download successfully!');
    }

}
