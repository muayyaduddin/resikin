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
use App\JenisSampah;
use App\Pembelian;
use App\BarangPembelian;


class PembelianController extends Controller{

    public function getDataProduk(Request $request){
        $idjenisproduk       = $request->idjenisproduk;

        if(empty($idjenisproduk)){

            return ResponseController::sendSuccess([
                'message' => 'Menampilkan semua data barang dan jenis produk.',
                'dataproduk' => Pembelian::allDataProduk(),
                'datajenisproduk' => Pembelian::allJenisProduk()
            ]);

        }else{

            return ResponseController::sendSuccess([
                'message' => 'Menampilkan data barang dan jenis produk by id.', 
                'dataproduk' => Pembelian::getDataProduk($idjenisproduk),
                'datajenisproduk' => Pembelian::getJenisProduk($idjenisproduk)
            ]);
        }
    }

    public function submitDataPembelian(Request $request){
        $idalamat               = $request->idalamat;
        $dataProduk             = $request->dataProduk;
        $iduser                 = $request->iduser;
        $totalpembelian         = $request->totalpembelian;

        if($idalamat == ""){
            return ResponseController::sendError(5); 
        }

        if(GlobalHelperController::getUserSaldo($iduser) < $totalpembelian){
            return ResponseController::sendError(17); 
        }

        //get alamat coordinates
        $alamatPositions = UserAlamat::find($idalamat);

        //get available driver in daerah
        $dataDrivers = User::getAvailablePembelianDrivers();
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

        //insert pembelian
        if(!empty($nearestDriver)){
            $idpembelian = Pembelian::createPembelian($idalamat, $iduser, $totalpembelian, $nearestDriver->id);

            //insert barang pembelian
            foreach($dataProduk as $val){
                BarangPembelian::createBarangPembelian($idpembelian,$val[0],$val[1]);
            }   

            GlobalHelperController::pushNotification($nearestDriver->fcmtoken, "Pembelian Baru", "Anda mendapatkan order pembelian, harap segera berikan response.", $idpembelian);

            return ResponseController::sendSuccess([
                'message' => 'Mohon tunggu, kurir '.$nearestDriver->nama.' akan segera menuju lokasi.', 
                'nearesDriver' => $nearestDriver,
                'datadistances' => $driversDistance
            ]);
        }

        return ResponseController::sendError(16); 
    }


    public function getDetailProduk(Request $request){
        $idproduk       = $request->idproduk;

        if($idproduk == ""){
            return ResponseController::sendError(5); 
        }

        $datadetail  = Pembelian::getDetailProduk($idproduk);

        return ResponseController::sendSuccess([
            'message' => 'Detail produk pembelian.', 
            'data' => $datadetail ? $datadetail[0] : json_decode("{}")
        ]);
    }


    public function getAlamatDefault(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Alamat default user.', 
            'data' => Pembelian::getAlamatDefault($iduser)
        ]);
    }

    public function getHistoryPembelian(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'History pembelian.', 
            'data' => Pembelian::getHistoryPembelian($iduser)
        ]);
    }

    public function getDetailPembelian(Request $request){
        $idpembelian       = $request->idpembelian;

        if($idpembelian == ""){
            return ResponseController::sendError(5); 
        }

        $tanggal = new DateTime();
        $detail = Pembelian::getDetailPembelian($idpembelian);

        return ResponseController::sendSuccess([
            'message' => 'Detail pembelian.', 
            'datadetailpembelian' => $detail ? $detail[0] : json_decode("{}"),
            'datadetailbarangbeli' => Pembelian::getDetailBarang($idpembelian),
            'qrcode' => GlobalHelperController::createQRCode($idpembelian."-".$tanggal->format('Ymdhis'))
        ]);
    }

}