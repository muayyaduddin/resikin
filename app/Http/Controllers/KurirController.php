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
use App\Kurir;
use App\PenjualanSampah;
use App\BarangPenjualan;
use App\Pembelian;


class KurirController extends Controller{

    public function updateStatusOrder(Request $request){
        $idpenjualan    = $request->idpenjualan;
        $foto           = $request->foto;

        if($idpenjualan == ""){
            return ResponseController::sendError(5); 
        }
        
        //update
        $status = Kurir::updateProgress($idpenjualan, $foto);

        $message = "Kurir dalam perjalanan menuju lokasi anda";
        $title = "Kurir dalam perjalanan";
        if($status == "2"){
            $title = "Penjualan Selesai";
            $message = "Kurir telah memproses penjualan anda";
        }

        $datauser = DB::table('user as a')
                ->select(DB::raw('a.fcmtoken'))
                ->join('penjualan as b', 'b.iduser', '=', 'a.id')
                ->where([ ['b.id', $idpenjualan]])
                ->first();
        GlobalHelperController::pushNotification($datauser->fcmtoken, $title, $message, $idpenjualan);

        if($status == 2){
            $penjualan = PenjualanSampah::find($idpenjualan);
            $title = "Penjualan Selesai";
            $message = "Saldo anda bertambah Rp ".number_format($penjualan->totalpenjualan);
            GlobalHelperController::pushNotification($datauser->fcmtoken, $title, $message, $idpenjualan);
        }

        return ResponseController::sendSuccess(['message' => 'Status progress berhasil diupdate']); 

    }

    public function updateStatusOrderPembelian(Request $request){
        $idpembelian    = $request->idpembelian;
        $foto           = $request->foto;

        if($idpembelian == ""){
            return ResponseController::sendError(5); 
        }
        
        //update
        $status = Pembelian::updateProgress($idpembelian, $foto);


        $message = "Kurir dalam perjalanan menuju toko";
        $title = "Kurir menuju toko";
        if($status == "2"){
            $title = "Kurir dalam perjalanan";
            $message = "Kurir dalam perjalanan menuju lokasi anda";
        }else if($status == "3"){  
            $title = "Barang telah diterima";
            $message = "Barang telah diantar ke lokasi anda";
        }

        $datauser = DB::table('user as a')
                ->select(DB::raw('a.fcmtoken'))
                ->join('pembelian as b', 'b.iduser', '=', 'a.id')
                ->where([ ['b.id', $idpembelian]])
                ->first();
                
        GlobalHelperController::pushNotification($datauser->fcmtoken, $title, $message, $idpembelian);

        return ResponseController::sendSuccess(['message' => 'Status progress berhasil diupdate ']); 
    }


    public function getDashboardKurir(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Dashboard kurir data aktif order.', 
            'data' => Kurir::dataAktifOrder($iduser)
        ]);
    }

    public function getHistoryWorkorder(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'History Work Order.', 
            'data' => Kurir::historyWorkOrder($iduser)
        ]);
    }

    public function getDetailWorkorder(Request $request){
        $idpenjualan       = $request->idpenjualan;

        if($idpenjualan == ""){
            return ResponseController::sendError(5); 
        }

        $tanggal = new DateTime();
        $infodetail = Kurir::detailWorkOrder($idpenjualan);

        return ResponseController::sendSuccess([
            'message' => 'Detail penjualan.', 
            'datadetailworkoder' => $infodetail ? $infodetail[0] : json_decode("{}"),
            'datadetailbarangjual' => Kurir::getDetailBarang($idpenjualan),
            'qrcode' => GlobalHelperController::createQRCode($idpenjualan."-".$tanggal->format('Ymdhis'))
        ]);
    }



}