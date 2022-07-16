<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('getformpendaftarandata', 'App\Http\Controllers\AuthController@getFormPendaftaranData');
Route::post('pendaftaranuser', 'App\Http\Controllers\AuthController@pendaftaranUser');
Route::post('checkotp', 'App\Http\Controllers\AuthController@checkOtp');
Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('accesscheck', 'App\Http\Controllers\AuthController@accessCheck');
Route::post('userprofile', 'App\Http\Controllers\AuthController@userprofil');

Route::post('generateqrcode', 'App\Http\Controllers\GlobalHelperController@generateQRCode');
Route::post('sendpushnotif', 'App\Http\Controllers\GlobalHelperController@sendnotification');

/* ALAMAT RESIKER */
Route::post('submitalamat', 'App\Http\Controllers\AlamatResikerController@pendaftaranAlamat');
Route::post('searchalamatresiker', 'App\Http\Controllers\AlamatResikerController@getAlamatResiker');
Route::post('hapusalamat', 'App\Http\Controllers\AlamatResikerController@hapusAlamat');
Route::post('getalamatdefault', 'App\Http\Controllers\AlamatResikerController@getAlamatDefault');

/* DAERAH */
Route::post('getlistdaerah', 'App\Http\Controllers\AlamatResikerController@getListDaerah');
Route::post('submitdaerah', 'App\Http\Controllers\DaerahController@submitDaerah');
Route::post('searchdaerah', 'App\Http\Controllers\DaerahController@getDaerah');
Route::post('hapusdaerah', 'App\Http\Controllers\DaerahController@hapusDaerah');

Route::post('getadmindaerah', 'App\Http\Controllers\VerificationController@getUserAdminDaerah');
Route::post('getdatakurir', 'App\Http\Controllers\VerificationController@getUserKurir');
Route::post('getdatamitra', 'App\Http\Controllers\VerificationController@getMitra');
Route::post('getdataresiker', 'App\Http\Controllers\VerificationController@getUserResiker');
Route::post('approveverification', 'App\Http\Controllers\VerificationController@approveVerification');

Route::post('uploadimage', 'App\Http\Controllers\GlobalHelperController@uploadImage');

/* MITRA */
Route::post('setinfomitra', 'App\Http\Controllers\MitraController@setInfoMitra');
Route::post('getdashboardmitra', 'App\Http\Controllers\MitraController@getDashboardMitra');
Route::post('gelaporanmitra', 'App\Http\Controllers\MitraController@getLaporanMitra');

/* PENJUALAN */
Route::post('getformdatajual', 'App\Http\Controllers\PenjualanController@getFormData');
Route::post('submitpenjualan', 'App\Http\Controllers\PenjualanController@submitDataPenjualan');
Route::post('gethistoryjual', 'App\Http\Controllers\PenjualanController@getHistoryPenjualan');
Route::post('getdetailpenjualan', 'App\Http\Controllers\PenjualanController@getDetailPenjualan');

/* PEMBELIAN */
Route::post('getdataprodukbeli', 'App\Http\Controllers\PembelianController@getDataProduk');
Route::post('getdetailprodukbeli', 'App\Http\Controllers\PembelianController@getDetailProduk');
Route::post('submitpembelian', 'App\Http\Controllers\PembelianController@submitDataPembelian');
Route::post('getdetailpembelian', 'App\Http\Controllers\PembelianController@getDetailPembelian');
Route::post('gethistorypembelian', 'App\Http\Controllers\PembelianController@getHistoryPembelian');

/* KURIR */
Route::post('getdashboardkurir', 'App\Http\Controllers\KurirController@getDashboardKurir');
Route::post('gethistoryworkorder', 'App\Http\Controllers\KurirController@getHistoryWorkorder');
Route::post('getdetailworkorder', 'App\Http\Controllers\KurirController@getDetailWorkorder');
Route::post('updatestatusworkorder', 'App\Http\Controllers\KurirController@updateStatusOrder');
Route::post('updatestatuspembelian', 'App\Http\Controllers\KurirController@updateStatusOrderPembelian');

/* PRODUK */
Route::post('submitproduk', 'App\Http\Controllers\ProdukController@submitProduk');
Route::post('getlistproduk', 'App\Http\Controllers\ProdukController@getListProduk');
Route::post('detailproduk', 'App\Http\Controllers\ProdukController@getDetailProduk');
Route::post('hapusproduk', 'App\Http\Controllers\ProdukController@hapusProduk');

/* JENIS PRODUK */
Route::post('submitjenisproduk', 'App\Http\Controllers\JenisProdukController@submitJenisProduk');
Route::post('getjenisproduk', 'App\Http\Controllers\JenisProdukController@getDataJenisProduk');
Route::post('hapusjenisproduk', 'App\Http\Controllers\JenisProdukController@hapusJenisProduk');

/* JENIS SAMPAH */
Route::post('submitjenissampah', 'App\Http\Controllers\JenisSampahController@submitJenisSampah');
Route::post('getjenissampah', 'App\Http\Controllers\JenisSampahController@getDataJenisSampah');
Route::post('hapusjenissampah', 'App\Http\Controllers\JenisSampahController@hapusJenisSampah');

/* RESIKER */
Route::post('getdashboardresiker', 'App\Http\Controllers\ResikerController@getDashboardResiker');
Route::post('gethistoryorder', 'App\Http\Controllers\ResikerController@getHistoryOrder');
Route::post('gethistorytransaksi', 'App\Http\Controllers\ResikerController@getTransactionHistory');

/* JADWAL PENGAMBILAN SAMPAH */
Route::post('submitjadwalpengambilan', 'App\Http\Controllers\JadwalPengambilanSampahController@submitJadwal');
Route::post('getjadwalpengambilan', 'App\Http\Controllers\JadwalPengambilanSampahController@getDataJadwalPengambilan');
Route::post('hapusjadwalpengambilan', 'App\Http\Controllers\JadwalPengambilanSampahController@hapusJadwalPengambilan');

/* TABUNGAN */
Route::post('getalltabungan', 'App\Http\Controllers\TabunganController@getDataTabungan');
Route::post('submitmastertabungan', 'App\Http\Controllers\TabunganController@submitDataTabungan');
Route::post('hapusmastertabungan', 'App\Http\Controllers\TabunganController@hapusTabungan');
Route::post('submittabunganresiker', 'App\Http\Controllers\TabunganController@submitTabunganResiker');

/* SEDEKAH */
Route::post('getallsedekah', 'App\Http\Controllers\SedekahController@getDataSedekah');
Route::post('submitmastersedekah', 'App\Http\Controllers\SedekahController@submitDataSedekah');
Route::post('hapusmastersedekah', 'App\Http\Controllers\SedekahController@hapusSedekah');
Route::post('createsedekahresiker', 'App\Http\Controllers\SedekahController@submitSedekahResiker');

/* Admin */
Route::post('getdashboardadmin', 'App\Http\Controllers\AdminController@getDashboardAdmin');
Route::post('getlaporanpenjualan', 'App\Http\Controllers\AdminController@getLaporanPenjualan');
Route::post('getlaporanpembelian', 'App\Http\Controllers\AdminController@getLaporanPembelian');
Route::post('getlaporantabungan', 'App\Http\Controllers\AdminController@getLaporanTabungan');
Route::post('getlaporansedekah', 'App\Http\Controllers\AdminController@getLaporanSedekah');