<?php

namespace Modules\Linen\Http\Services;

use Modules\Linen\Dao\Models\ReturDetail;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Alert;

class ReturCreateService
{
    public function save(CrudInterface $repository, $data)
    {
        $check = false;
        try {
            $check = $repository->saveRepository($data->all());
            ReturDetail::insert($data['detail']);
            if(isset($check['status']) && $check['status']){

                Alert::create($data['rfid']);
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
}