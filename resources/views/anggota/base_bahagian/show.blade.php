<div class="table-responsive">
    <form id="frm-profil-kemaskini">
        <table class="table table-bordered">
            <input id="txtDepartmentId" type="hidden" value="{{ $profil->USERID }}">
            <tbody>
                <tr>
                    <td><b>BAHAGIAN/ UNIT</b></td>
                    <td>
                        <div style="position: relative;">
                            <input id="departmentDisplay" class="form-control departmentDisplay" type="text" value="{{ $profil->department->DEPTNAME }}" style="background-color: #FFF;" readonly>
                            <input id="departmentDisplayId" name="txtDepartmentId" class="form-control departmentDisplayId" type="hidden" value="{{ $profil->DEFAULTDEPTID }}" style="background-color: #FFF;" readonly>
                            <div id="treeDisplay" style="display:none;">
                                <div id="departmentsTree" style="position:absolute; background-color: #FFF; overflow:auto; max-height:200px; border:1px #ddd solid"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </body>
        </table>

        @can('edit-profil')
        <button class="btn btn-success pull-right btn-kemaskini-simpan" type="submit">SIMPAN</button>
        <button id="btn-batal" type="button" class="btn btn-link pull-right" style="color:#dd4b39;" >BATAL</button>
        @endcan
    </form>
</div>