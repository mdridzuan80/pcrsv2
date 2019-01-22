<?php

namespace App\Transformers;

use App\FlowBahagian;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class FlowBahagianTransformer extends TransformerAbstract
{
    public function transform($flowBahagian)
    {
        return [
            'flow' => ($flowBahagian) ? $flowBahagian->flag : FlowBahagian::BIASA,
        ];
    }
}