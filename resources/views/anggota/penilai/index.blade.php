<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #f5f5f5;">
                            <th class="col-md-6"><i class="fa fa-fw fa-user"></i> PEGAWAI PENILAI PERTAMA</th>
                            <th class="col-md-5">JAWATAN</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-6">{{ ($penilai->isNotEmpty()) ? optional($penilai[\App\PegawaiPenilai::FLAG_PEGAWAI_PERTAMA])[0]->Name : 'Tiada' }}</td>
                            <td class="col-md-5">{{ ($penilai->isNotEmpty()) ? optional($penilai[\App\PegawaiPenilai::FLAG_PEGAWAI_PERTAMA])[0]->TITLE : 'Tiada' }}</td>
                            <td>
                            <button id="btn-ppp1-edit" type="button" class="btn btn-primary btn-block btn-sm pull-right btn-ppp-edit" title="Kemaskini maklumat pegawai penilai pertama" data-pegawai_flag="{{ \App\PegawaiPenilai::FLAG_PEGAWAI_PERTAMA }}">{{ ($penilai->isNotEmpty() && optional($penilai[\App\PegawaiPenilai::FLAG_PEGAWAI_PERTAMA])) ? 'KEMASKINI' : 'TAMBAH'}}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #f5f5f5;">
                            <th class="col-md-6"><i class="fa fa-fw fa-user"></i> PEGAWAI PENILAI KEDUA</th>
                            <th class="col-md-5">JAWATAN</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-md-6">{{ ($penilai->isNotEmpty()) ? optional($penilai[\App\PegawaiPenilai::FLAG_PEGAWAI_KEDUA])[0]->Name : 'Tiada' }}</td>
                            <td class="col-md-5">{{ ($penilai->isNotEmpty()) ? optional($penilai[\App\PegawaiPenilai::FLAG_PEGAWAI_KEDUA])[0]->TITLE : 'Tiada' }}</td>
                            <td>
                                <button id="btn-ppp2-edit" type="button" class="btn btn-primary btn-block btn-sm pull-right btn-ppp-edit" title="Kemaskini maklumat pegawai penilai kedua" data-pegawai_flag="{{ \App\PegawaiPenilai::FLAG_PEGAWAI_KEDUA }}">{{ ($penilai->isNotEmpty() && optional($penilai[\App\PegawaiPenilai::FLAG_PEGAWAI_KEDUA])) ? 'KEMASKINI' : 'TAMBAH'}}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
