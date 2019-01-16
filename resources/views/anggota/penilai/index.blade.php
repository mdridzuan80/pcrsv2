<div class="row">
    <div class="col-md-12">
        <div class="box">
            <form id="frm-ppp" method="post" role="form">
                <input type="hidden" name="_method" value="PUT">
                <div class="box-body">
                    @if ($profil->OPHONE)
                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color: #f5f5f5;">
                                <th>PEGAWAI PENILAI PERTAMA</th>
                                <th>JAWATAN</th>
                                <th>PEGAWAI PENILAI KEDUA</th>
                                <th>JAWATAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input id="ppp_userid" type="hidden" name="" value="{{ $profil->penilai->USERID }}">
                                    {{ $profil->penilai->Name }}
                                </td>
                                <td>{{ $profil->penilai->TITLE }}</td>
                                <td>{{ $profil->penilai->penilai->Name }}</td>
                                <td>{{ $profil->penilai->penilai->TITLE }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <div class="callout callout-warning">
                        <h4>MAKLUMAT PEGAWAI PENILAI TIDAK LENGKAP!</h4>
                    </div>
                    @endif
                    <div id="pilih-ppp-panel" style="display:none;">
                        <h4><b>PEMILIHAN PEGAWAI PENILAI PERTAMA</b></h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div id="panel-department2" class="panel panel-default" >
                                    <div class="panel-heading">
                                        <div><i class="fa fa-sitemap fa-fw"></i> Bahagian/Unit</div>
                                        <div class="checkbox">
                                            <label>
                                                <input id="sub-dept2" type="checkbox"> Sub Jabatan
                                            </label>
                                        </div>
                                    </div>
                                    <div class="panel-body" style="overflow:auto;">
                                        <div id="departments2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select id="comSenPPP" class="form-control" size='20' name="comSenPPP"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button id="btn-ppp-edit" type="button" class="btn btn-primary btn-block pull-right" title="Kemaskini maklumat pegawai penilai">KEMASKINI</button>
                    <span id="pilih-ppp-simpan-panel" class="pull-right" style="display:none;">
                        <button id="btn-ppp-batal" type="button" class="btn btn-link" style="color:#dd4b39;" title="Kemaskini maklumat pegawai penilai">BATAL</button>
                        <button id="btn-ppp-simpan" type="submit" class="btn btn-success" title="Kemaskini maklumat pegawai penilai">SIMPAN</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
