<?php

namespace App\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class Anggota extends TransformerAbstract
{
    public function transform($anggota)
    {
        return [
            'anggota_id' => $anggota->USERID,
            'nama' => $anggota->Name,
            'jawatan' => $anggota->TITLE,
        ];
    }
}