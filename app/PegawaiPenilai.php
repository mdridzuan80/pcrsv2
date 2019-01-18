<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PegawaiPenilai extends Model
{
    const FLAG_PEGAWAI_PERTAMA = 1;
    const FLAG_PEGAWAI_KEDUA = 2;

    protected $table = 'pegawai_penilai';

    public function personel()
    {
        return $this->belongsTo(anggota::class);
    }

    public function penilai()
    {
        return $this->belongsTo(anggota::class, 'pegawai_id');
    }
}
