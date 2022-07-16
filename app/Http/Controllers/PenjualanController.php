<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use DateTime;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\GlobalHelperController;
use App\User;
use App\UserAlamat;
use App\Daerah;
use App\JadwalAmbilSampah;
use App\JenisSampah;
use App\PenjualanSampah;
use App\BarangPenjualan;


class PenjualanController extends Controller{

    public function getFormData(Request $request){
        $iddaerah       = $request->iddaerah;

        if($iddaerah == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Detail data produk.', 
            'datajadwalpengambilan' => PenjualanSampah::getJadwalAmbil($iddaerah),
            'datajenissampah' => PenjualanSampah::getJenisSampah($iddaerah)
        ]);
    }

    public function submitDataPenjualan(Request $request){
        $idalamat               = $request->idalamat;
        $dataSampah             = $request->dataSampah;
        $fotoname               = $request->fotoname;
        $idjadwal               = $request->idjadwalpengambilan;
        $iduser                 = $request->iduser;

        if($idalamat == ""){
            return ResponseController::sendError(5); 
        }

        //get alamat coordinates
        $alamatPositions = UserAlamat::find($idalamat);

        //get available driver in daerah
        $dataDrivers = User::getAvailableDrivers($idjadwal);
        $driversDistance = GlobalHelperController::getDriversDistance($alamatPositions->gpsl, $alamatPositions->gpsb);
        $driversDistance = !empty($driversDistance) ? json_decode($driversDistance) : [];
        $nearestDistance = -1;
        $nearestDriver = [];
        foreach($driversDistance as $dis){
            $driverAvailable = false;
            $driverChoosen = [];
            foreach($dataDrivers as $driver){
                if($driver->deviceid == $dis->driverId){
                    $driverAvailable = true;
                    $driverChoosen = $driver;
                }
            }   
            if($driverAvailable){
                if($nearestDistance == -1 || $nearestDistance > $dis->distance){
                    $nearestDistance = $dis->distance;
                    $nearestDriver = $driverChoosen;
                    $nearestDriver->distance = number_format((float)$dis->distance, 2,".",",").' km';
                }
            }
        }

        //insert penjualan
        if(!empty($nearestDriver)){
            //get total penjualan
            $totalpenjualan = 0;
            foreach($dataSampah as $val){
                $totalpenjualan += JenisSampah::getHargaJenis($val[0],$val[1]);
            }   

            $idpenjualan = PenjualanSampah::createPenjualan($idalamat, $iduser, $idjadwal, $fotoname, $totalpenjualan, $nearestDriver->id);

            //insert barang penjualan
            foreach($dataSampah as $val){
                BarangPenjualan::createBarangPenjualan($idpenjualan,$val[0],$val[1]);
            }   

            GlobalHelperController::pushNotification($nearestDriver->fcmtoken, "Penjualan Baru", "Anda mendapatkan order penjualan baru", $idpenjualan);
            
            return ResponseController::sendSuccess([
                'message' => 'Data penjualan berhasil disimpan.', 
                'nearesDriver' => $nearestDriver,
                'datadistances' => $driversDistance,
                'idpenjualan' => $idpenjualan,
                'datasampah' => $dataSampah
            ]);
        }

        return ResponseController::sendError(16); 
    }

    public function getDetailPenjualan(Request $request){
        $idpenjualan       = $request->idpenjualan;

        if($idpenjualan == ""){
            return ResponseController::sendError(5); 
        }

        $tanggal = new DateTime();
        $infodetail = PenjualanSampah::getDetailPenjualan($idpenjualan);

        return ResponseController::sendSuccess([
            'message' => 'Detail penjualan.', 
            'datadetailpenjualan' => $infodetail ? $infodetail[0] : json_decode("{}"),
            'datadetailbarangjual' => PenjualanSampah::getDetailBarang($idpenjualan),
            'qrcode' => GlobalHelperController::createQRCode($idpenjualan."-".$tanggal->format('Ymdhis'))
        ]);
    }

}