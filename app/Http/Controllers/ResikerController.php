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
use App\UserLevel;
use App\UserAlamat;
use App\Resiker;
use App\Otp;
use App\PenjualanSampah;
use App\Pembelian;
use App\Tabungan;
use App\Sedekah;



class ResikerController extends Controller{

	public function getDashboardResiker(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }
        $saldo = GlobalHelperController::getUserSaldo($iduser);
        
        $user = User::getUser($iduser);
        $iddaerah = $user->iddaerah;
        if(UserLevel::isUserPenggunaUmum($user->idlevel)){
            $alamatdefault = UserAlamat::getDefault($iduser);
            $iddaerah  = $alamatdefault->iddaerah;
        }

        return ResponseController::sendSuccess([
            'message' => 'Dashboard Resiker data 6 barang terbaru.', 
            'data' => Resiker::produkTerbaru($iddaerah),
            'saldo' => $saldo
        ]);
    }


    public function getHistoryOrder(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'History penjualan.', 
            'data' => PenjualanSampah::getHistoryOrder($iduser)
        ]);
    }

    public function getTransactionHistory(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'History transaksi.', 
            'datahistory' => PenjualanSampah::getTransactionHistory($iduser),
            'saldo' => GlobalHelperController::getUserSaldo($iduser)
        ]);
    }
}