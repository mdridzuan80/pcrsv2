<?php

namespace App\Transformers;

use App\Flow;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class FlowBahagianTransformer extends TransformerAbstract
{
    public function transform($flowBahagian)
    {
        return [
            'flow' => ($flowBahagian) ? $flowBahagian->flag : Flow::BIASA,
        ];
    }
}