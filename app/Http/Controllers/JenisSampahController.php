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
use App\InfoMitra;
use App\JenisSampah;



class JenisSampahController extends Controller{

  	public function submitJenisSampah(Request $request){
        $iduser         = $request->iduser;
        $idjenissampah  = $request->idjenissampah;
        $iddaerah       = $request->iddaerah;
        $nama           = $request->nama;
        $harga          = $request->harga;
        $satuan         = $request->satuan;

        if($iddaerah == "" || $nama == ""){
            return ResponseController::sendError(5); 
        }

        if(empty($idjenissampah)){
            //createnew
            if(JenisSampah::checkNamaExist($iddaerah, $nama)){
                return ResponseController::sendError(13); 
            }
            $newjp = JenisSampah::createJenisSampah($iddaerah, $nama, $harga, $satuan);
        }else{
            //update
            JenisSampah::updateJenisSampah($idjenissampah, $nama, $harga, $satuan);
        }

        return ResponseController::sendSuccess(['message' => 'Jenis sampah berhasil '.(empty($idjenissampah) ? 'didaftarkan.' : 'diubah')]); 
  	}

    public function getDataJenisSampah(Request $request){
        $iduser         = $request->iduser;
        $iddaerah       = $request->iddaerah;

        if($iddaerah == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Data jenis sampah.', 
            'data' => JenisSampah::getListJenisSampah($iddaerah)
        ]); 
    }

    public function hapusJenisSampah(Request $request){
        $iduser         = $request->iduser;
        $idjenissampah  = $request->idjenissampah;

        if($idjenissampah == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        JenisSampah::hapusJenisSampah($idjenissampah);

        return ResponseController::sendSuccess(['message' => 'Jenis sampah berhasil dihapus']); 
    }
}