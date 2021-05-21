<?php

namespace Modules\Linen\Http\Services;

use Modules\Linen\Dao\Facades\MasterOutstandingFacades;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Notes;

class OutstandingBatchService
{
    public function save($repository, $data)
    {
        $check = false;
        try {
            $exists = $repository->batchSelectRepository(array_keys($data->detail))->get();
            
            if(!empty($exists)){
                $data_rfid = $exists->pluck('linen_outstanding_rfid');
                $repository->batchDeleteRepository($data_rfid);
            }
           
            $check = $repository->batchSaveRepository($data->detail);

            $session = $data->linen_outstanding_session;
            if($session)
            {
                $master = MasterOutstandingFacades::where('linen_master_outstanding_session', $session)->first();
                if($master){
                    $initial = $master->linen_master_outstanding_total ?? 0;
                    $total = $master->outstanding->count() ?? 0;
                    if($total >= $initial){
                        $master->linen_master_outstanding_status = 2;
                        $master->save();
                    }
                }
            }

            if(isset($check['status']) && $check['status']){

                Alert::create();
            }
            else{
                $message = env('APP_DEBUG') ? $check['data'] : $check['message'];
                Alert::error($message);
            }
        } catch (\Throwable $th) {
            Alert::error($th->getMessage());
            return $th->getMessage();
        }

        return $check;
    } 

    public function update($repository, $data)
    {
        $where = $data->data;
        $update = $data->all();
        unset($update['data']);
        unset($update['type']);
        $pull = $repository->WhereIn('linen_outstanding_rfid', $where);
        $check = $pull->update($update);
        if ($check) {
            if(request()->wantsJson()){
                $notes = Notes::update($data->all());
                return response()->json($notes)->getData();
            }
            
            Alert::update();

        } else {

            return Notes::error($data);
            Alert::error($data);
        }
        return $check;
    }
}
