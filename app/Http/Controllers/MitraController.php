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
use App\BarangPembelian;



class MitraController extends Controller{

  	public function setInfoMitra(Request $request){
        $iduser         = $request->iduser;
        $iddaerah       = $request->iddaerah;
        $fotoname       = $request->fotoname;
        $nama           = $request->nama;
        $gpsl           = $request->gpsl;
        $gpsb           = $request->gpsb;
        $alamat         = $request->alamat;

        if($iduser == "" || $nama == "" || $alamat == ""){
            return ResponseController::sendError(5); 
        }

        $newAlamat = InfoMitra::createInfo($iduser, $iddaerah, $nama, $fotoname, $gpsl, $gpsb, $alamat);

        return ResponseController::sendSuccess(['message' => 'Info mitra berhasil diperbarui.']); 
  	}

    public function getDashboardMitra(Request $request){
        $iduser         = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Info mitra berhasil diperbarui.',
            'datamitra' => InfoMitra::getInfoMitra($iduser)
        ]); 
    }

    public function getLaporanMitra(Request $request){
        $iduser         = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }
        
        return ResponseController::sendSuccess([
            'message' => 'data laporan mitra',
            'laporan' => BarangPembelian::getLaporanMitra($iduser)
        ]); 
    }
}