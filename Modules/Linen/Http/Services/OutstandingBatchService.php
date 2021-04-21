<?php

namespace Modules\Linen\Http\Services;

use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Notes;

class OutstandingBatchService
{
    public function save($repository, $data)
    {
        $check = false;
        try {
            $check = $repository->batchRepository($data->detail);
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
