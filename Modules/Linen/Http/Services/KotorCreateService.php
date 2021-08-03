<?php

namespace Modules\Linen\Http\Services;

use Modules\Linen\Dao\Models\KotorDetail;
use Modules\Linen\Dao\Models\Outstanding;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Notes;

class KotorCreateService
{
    public function save(CrudInterface $repository, $data)
    {
        $check = false;
        try {
            
            $check = $repository->saveRepository($data->all());
            KotorDetail::insert($data['kotor']);
            Outstanding::insert($data['outstanding']);

            if(isset($check['status']) && $check['status']){

                Alert::create();
                $check = Notes::create(array_keys($data['outstanding']));
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
