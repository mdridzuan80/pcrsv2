<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();

Route::middleware('auth:internal,ldap')->group(function () {
    Route::get('/', 'DashboardController@index');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::middleware('can:view-anggota')->group(function () {
        Route::get('/anggota', 'AnggotaController@index')->name('anggota');
    });

    Route::middleware('can:view-pengguna')->group(function () {
        Route::get('/pengguna', 'PenggunaController@index')->name('pengguna');
    });

    Route::middleware('can:view-setting')->group(function () {
        Route::get('/konfigurasi', 'KonfigurasiController@index')->name('konfigurasi');
    });

    //Local API
    Route::prefix('rpc')->middleware('ajax')->group(function () {
        // Department
        Route::get('/switch_role/{role}', 'RoleController@switchRole');
        Route::get('/department_tree', 'DepartmentController@departmentTree');

        // Anggota
        Route::post('/anggota_grid', 'AnggotaController@rpcAnggotaGrid')->middleware('can:view-anggota');
        Route::post('/anggota_penilai_grid', 'AnggotaController@rpcAnggotaPenilaiGrid')->middleware('can:view-anggota');
        Route::get('/anggota/{profil}', 'AnggotaController@rpcShow')->middleware('can:view-profil');
        Route::post('/anggota/{profil}', 'AnggotaController@rpcUpdate')->middleware('can:edit-profil');

        Route::prefix('anggota')->group(function () {
            // Waktu Berperingkat
            Route::get('/waktu_bekerja/{profil}', 'WaktuBerperingkatController@rpcIndex')->middleware('can:view-waktu_bekerja');
            Route::get('/waktu_bekerja_bulanan/{profil}/{tahun}', 'WaktuBerperingkatController@rpcBulanan')->middleware('can:view-waktu_bekerja');
            Route::post('/waktu_bekerja_bulanan/{profil}', 'WaktuBerperingkatController@rpcBulananCreate')->middleware('can:add-waktu_bekerja');
            Route::delete('/waktu_bekerja_bulanan/{profil}/{id}', 'WaktuBerperingkatController@rpcDelete')->middleware('can:delete-waktu_bekerja');
            Route::get('/waktu_bekerja_harian/{profil}/{tahun}', 'WaktuBerperingkatController@rpcBulanan')->middleware('can:view-waktu_bekerja');
            Route::post('/waktu_bekerja_harian/{profil}', 'WaktuBerperingkatController@rpcHarianCreate')->middleware('can:add-waktu_bekerja');
            Route::delete('/waktu_bekerja_harian/{profil}/{id}', 'WaktuBerperingkatController@rpcDelete')->middleware('can:delete-waktu_bekerja');
            
            // Pegawai Penilai
            Route::get('/{profil}/penilai', 'AnggotaController@rpcPenilaiIndex')->middleware('can:view-penilai');
            Route::post('/{profil}/penilai', 'AnggotaController@rpcPenilaiUpdate')->middleware('can:edit-penilai');

            //Base Bahagian
            Route::get('/{profil}/basebahagian', 'AnggotaController@rpcBaseBahagianShow')->middleware('can:view-base-bahagian');
            Route::post('/{profil}/basebahagian', 'AnggotaController@rpcBaseBahagianStore')->middleware('can:edit-base-bahagian');

            //Flow Profil
            Route::get('/{profil}/flow', 'AnggotaController@rpcFlowShow')->middleware('can:view-flow-profil');
            Route::post('/{profil}/flow', 'AnggotaController@rpcFlowUpdate')->middleware('can:edit-flow-profil');

        });

        Route::prefix('pengguna')->group(function () {
            Route::get('/{profil}/login', 'PenggunaController@rpcLoginIndex')->middleware('can:view-login');
            Route::post('/{profil}/login', 'PenggunaController@rpcLoginStore')->middleware('can:add-login');
            Route::post('/ldap', 'PenggunaController@rpcSearchLdap')->middleware('can:view-login');

            Route::post('/{profil}/peranan', 'PenggunaController@rpcPerananStore')->middleware('can:add-peranan');
            Route::delete('/peranan/{roleUser}', 'PenggunaController@rpcPerananDestroy')->middleware('can:delete-peranan');
        });

        Route::prefix('kalendar')->group(function () {
            Route::get('/{profil}', 'KalendarController@rpcEventAnggotaIndex');
            Route::get('/{profil}/acara/create', 'KalendarController@rpcEventAnggotaCreate');
            Route::post('/{profil}/acara', 'KalendarController@rpcEventAnggotaStore');
            Route::get('/{profil}/acara/{tarikh}', 'KalendarController@rpcEventAnggotaShow2');
        });

        Route::prefix('konfigurasi')->group(function () {
            Route::get('/flow_bahagian/{department}', 'KonfigurasiController@rpcFlowBahagianShow')->middleware('can:edit-flow-bahagian-setting');
            Route::post('/flow_bahagian/{department}', 'KonfigurasiController@rpcFlowBahagianUpdate')->middleware('can:edit-flow-bahagian-setting');
        });
    });
});