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
use App\UserLevel;
use App\Pembelian;
use App\Tabungan;
use App\TabunganUser;


class TabunganController extends Controller{

    public function getDataTabungan(Request $request){
        $iduser           = $request->iduser;
        $iddaerah         = $request->iddaerah;

        $datatabungan     = Tabungan::where([ ['status', '1'] ])->get();

        $user = User::getUser($iduser);
        if(UserLevel::isUserPenggunaUmum($user->idlevel)){
            $alamatdefault = UserAlamat::getDefault($iduser);
            $datatabungan  = Tabungan::getTabunganByUser($iduser);
        }

        return ResponseController::sendSuccess([
            'message' => 'data tabungan',
            'tabungan' => $datatabungan,
        ]);
    }

    public function submitDataTabungan(Request $request){
        $iddaerah           = $request->iddaerah;
        $idtabungan         = $request->idtabungan;
        $nama               = $request->nama;
        $deskripsi          = $request->deskripsi;

        if(empty($deskripsi) || empty($nama)){
            return ResponseController::sendError(5); 
        }

        if(empty($idtabungan)){
            $idtabungan = Tabungan::createTabungan($nama, $deskripsi, $iddaerah);
        }else{
            Tabungan::updateTabungan($idtabungan, $nama, $deskripsi);
        }

        return ResponseController::sendSuccess([
            'message' => 'Tabungan berhasil '.(empty($idjenisproduk) ? 'didaftarkan.' : 'diubah')
        ]);
    }

    public function hapusTabungan(Request $request){
        $iduser         = $request->iduser;
        $idtabungan  = $request->idtabungan;

        if($idtabungan == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        Tabungan::hapusTabungan($idtabungan);

        return ResponseController::sendSuccess(['message' => 'Tabungan berhasil dihapus']); 
    }

    public function submitTabunganResiker(Request $request){
        $iduser         = $request->iduser;
        $idtabungan     = $request->idtabungan;
        $nominal        = $request->nominal;

        if($idtabungan == "" || $iduser == "" || $nominal == ""){
            return ResponseController::sendError(5); 
        }

        if(GlobalHelperController::getUserSaldo($iduser) < $nominal){
            return ResponseController::sendError(17); 
        }
        
        $tabunganresiker = TabunganUser::createTabunganResiker($idtabungan, $iduser, $nominal);

        $tabungan = Tabungan::find($idtabungan);

        $datauser = User::find($iduser);
        GlobalHelperController::pushNotification($datauser->fcmtoken, "Tabungan bertambah", $tabungan->nama." anda telah bertambah Rp ".number_format($nominal), $tabunganresiker);

        return ResponseController::sendSuccess(['message' => 'Tabungan berhasil ditambahkan']); 
    }

}