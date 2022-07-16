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
use App\Daerah;
use App\Otp;
use App\PenjualanSampah;
use App\Pembelian;
use App\Tabungan;
use App\Sedekah;
use App\TabunganUser;
use App\SedekahUser;



class AdminController extends Controller{

	public function getDashboardAdmin(Request $request){
        $iduser       = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        $iddaerah       = $request->iddaerah;
        $user = User::getUser($iduser);
        if(UserLevel::isUserAdminDaerah($user->idlevel)){
            $iddaerah  = $user->iddaerah;
        }

        return ResponseController::sendSuccess([
            'message' => 'Dashboard admin', 
            'lasttransaction' => PenjualanSampah::getLastTransaction($iddaerah)
        ]);
    }

    public function getLaporanPenjualan(Request $request){
        $iduser       = $request->iduser;
        $iddaerah       = $request->iddaerah;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        $user = User::getUser($iduser);
        if(UserLevel::isUserAdminDaerah($user->idlevel)){
            $iddaerah  = $user->iddaerah;
        }

        return ResponseController::sendSuccess([
            'daerah' => Daerah::where([['status', '1']])->get(),
            'laporan' => PenjualanSampah::getLaporanPenjualanAdmin($iddaerah)
        ]);
    }

    public function getLaporanPembelian(Request $request){
        $iduser       = $request->iduser;
        $iddaerah       = $request->iddaerah;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        $user = User::getUser($iduser);
        if(UserLevel::isUserAdminDaerah($user->idlevel)){
            $iddaerah  = $user->iddaerah;
        }

        return ResponseController::sendSuccess([
            'daerah' => Daerah::where([['status', '1']])->get(),
            'laporan' => Pembelian::getLaporanPembelian($iddaerah)
        ]);
    }

    public function getLaporanTabungan(Request $request){
        $iduser         = $request->iduser;
        $iddaerah       = $request->iddaerah;
        $idtabungan     = $request->idtabungan;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

       
        // $user = User::getUser($iduser);
        // if(UserLevel::isUserAdminDaerah($user->idlevel)){
        //     $iddaerah  = $user->iddaerah;
        // }

        $datatabungan     = Tabungan::where([ ['status', '1'] ])->get();

        return ResponseController::sendSuccess([
            'tabungan' => $datatabungan,
            'daerah' => Daerah::where([['status', '1']])->get(),
            'laporan' => TabunganUser::getLaporantabungan($iddaerah, $idtabungan)
        ]);
    }

    public function getLaporanSedekah(Request $request){
        $iduser       = $request->iduser;
        $iddaerah       = $request->iddaerah;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        $user = User::getUser($iduser);
        if(UserLevel::isUserAdminDaerah($user->idlevel)){
            $iddaerah  = $user->iddaerah;
        }

        return ResponseController::sendSuccess([
            'daerah' => Daerah::where([['status', '1']])->get(),
            'laporan' => SedekahUser::getLaporansedekah($iddaerah)
        ]);
    }
}