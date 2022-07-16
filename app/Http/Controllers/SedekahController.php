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
use App\JenisSampah;
use App\Pembelian;
use App\Sedekah;
use App\SedekahUser;


class SedekahController extends Controller{

    public function getDataSedekah(Request $request){
        $iduser           = $request->iduser;
        $iddaerah           = $request->iddaerah;

        $user = User::getUser($iduser);
        if(UserLevel::isUserPenggunaUmum($user->idlevel)){
            $alamatdefault = UserAlamat::getDefault($iduser);
            $iddaerah  = $alamatdefault->iddaerah;
        }

        return ResponseController::sendSuccess([
            'message' => 'data sedekah',
            'sedekah' => Sedekah::where([ ['status', '1'], ['iddaerah', $iddaerah] ])->get(),
            'iddaerah' => $iddaerah 
        ]);
    }

    public function submitDataSedekah(Request $request){
        $iddaerah           = $request->iddaerah;
        $idsedekah          = $request->idsedekah;
        $nama               = $request->nama;
        $deskripsi          = $request->deskripsi;

        if(empty($deskripsi) || empty($nama)){
            return ResponseController::sendError(5); 
        }

        if(empty($idsedekah)){
            $idsedekah = Sedekah::createSedekah($nama, $deskripsi, $iddaerah);
        }else{
            Sedekah::updateSedekah($idsedekah, $nama, $deskripsi);
        }

        return ResponseController::sendSuccess([
            'message' => 'Sedekah berhasil '.(empty($idjenisproduk) ? 'didaftarkan.' : 'diubah')
        ]);
    }

    public function hapusSedekah(Request $request){
        $iduser         = $request->iduser;
        $idsedekah      = $request->idsedekah;

        if($idsedekah == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        Sedekah::hapusSedekah($idsedekah);

        return ResponseController::sendSuccess(['message' => 'Sedekah berhasil dihapus']); 
    }

    public function submitSedekahResiker(Request $request){
        $iduser         = $request->iduser;
        $idsedekah      = $request->idsedekah;
        $nominal        = $request->nominal;

        if($idsedekah == "" || $iduser == "" || $nominal == ""){
            return ResponseController::sendError(5); 
        }

        if(GlobalHelperController::getUserSaldo($iduser) < $nominal){
            return ResponseController::sendError(17); 
        }

        $tabunganresiker = SedekahUser::createSedekahResiker($idsedekah, $iduser, $nominal);

        $sedekah = Sedekah::find($idsedekah);

        $datauser = User::find($iduser);
        GlobalHelperController::pushNotification($datauser->fcmtoken, "Sedekah Kami Terima", "Terima kasih telah menyisihkan rezeki anda untuk ".$sedekah->nama." sebesar Rp ".number_format($nominal), $tabunganresiker);

        return ResponseController::sendSuccess(['message' => 'Sedekah berhasil ditambahkan']); 
    }
}