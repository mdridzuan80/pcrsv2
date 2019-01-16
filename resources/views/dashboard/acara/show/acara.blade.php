<div class="table-responsive">
    <form id="frm-profil-kemaskini">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td class="col-md-3"><b>PERKARA</b></td>
                    <td><input class="form-control" type="text" name="txtPerkara" placeholder="Perkara" value="{{ $event->title }}" required></td>
                </tr>
                <tr>
                    <td class="col-md-3"><b>MASA MULA</b></td>
                    <td><input class="form-control" type="text" name="txtMasaMula" placeholder="Masa Mula" value="{{ \Carbon\Carbon::parse($event->start)->format('d-m-Y g:m A') }}" required></td>
                </tr>
                <tr>
                    <td class="col-md-3"><b>MASA TAMAT</b></td>
                    <td><input class="form-control" type="text" name="txtMasaTamat" placeholder="Masa Tamat" value="{{ \Carbon\Carbon::parse($event->end)->format('d-m-Y g:m A') }}" required></td>
                </tr>
            </body>
        </table>

        <button class="btn btn-success pull-right btn-kemaskini-simpan" type="submit">SIMPAN</button>
        <button id="btn-batal" type="button" class="btn btn-link pull-right" style="color:#dd4b39;" >BATAL</button>
    </form>
</div>