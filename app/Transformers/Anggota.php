<?php

namespace App\Transformers;

use App\Anggota as MAnggota;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class Anggota extends TransformerAbstract
{
    protected $availableIncludes = [
        'flowbahagian',
        'flowanggota',
    ];

    public function transform(MAnggota $anggota)
    {
        return [
            'anggota_id' => $anggota->USERID,
            'nama' => $anggota->Name,
            'jawatan' => $anggota->TITLE,
        ];
    }

    public function includeFlowbahagian(MAnggota $anggota)
    {
        return $this->item(optional(optional($anggota)->xtraAttr)->flowBaseDepartment, new FlowBahagianTransformer);
    }

    public function includeFlowanggota(MAnggota $anggota)
    {
        return $this->item($anggota->flow, new FlowAnggotaTransformer);
    }
}