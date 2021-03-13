<?php

namespace Modules\System\Http\Services;

use Modules\System\Dao\Interfaces\CrudInterface;

class SingleService
{
    public function get(CrudInterface $repository, $code, $relation = false)
    {
        return $repository->singleRepository($code, $relation);
    }
}
