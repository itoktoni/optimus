<?php

namespace Modules\System\Http\Services;

class PreviewService
{
    public function data($repository, $code, $relation = false)
    {
        $linen = $repository;
        $data = $code->all();
        $preview = null;
        
        if (isset($data['from']) && isset($data['to'])) {

            if ($data['from']) {
                $linen = $linen->whereDate('item_linen_created_at', '>=', $data['from']);
            }
            if ($data['to']) {
                $linen = $linen->whereDate('item_linen_created_at', '<=', $data['to']);
            }

            $preview = $linen->filter()->get();
        }

        return $preview;
    }
}
