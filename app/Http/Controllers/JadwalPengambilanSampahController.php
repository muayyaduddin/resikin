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
use App\JadwalAmbilSampah;



class JadwalPengambilanSampahController extends Controller{

  	public function submitJadwal(Request $request){
        $iduser         = $request->iduser;
        $idjadwal       = $request->idjadwal;
        $iddaerah       = $request->iddaerah;
        $jam            = $request->jam;

        if($iddaerah == "" || $jam == ""){
            return ResponseController::sendError(5); 
        }

        if(empty($idjadwal)){
            //createnew
            if(JadwalAmbilSampah::checkJamExist($iddaerah, $jam)){
                return ResponseController::sendError(13); 
            }
            $newjp = JadwalAmbilSampah::createJam($iddaerah, $jam);
        }else{
            //update
            JadwalAmbilSampah::updateJadwalAmbil($idjadwal, $jam);
        }

        return ResponseController::sendSuccess(['message' => 'Jadwal pengambilan berhasil '.(empty($idjadwal) ? 'didaftarkan.' : 'diubah')]); 
  	}

    public function getDataJadwalPengambilan(Request $request){
        $iduser         = $request->iduser;
        $iddaerah       = $request->iddaerah;

        if($iddaerah == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Data jadwal pengambilan.', 
            'data' => JadwalAmbilSampah::getListJam($iddaerah)
        ]); 
    }

    public function hapusJadwalPengambilan(Request $request){
        $iduser         = $request->iduser;
        $idjadwal  = $request->idjadwal;

        if($idjadwal == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        JadwalAmbilSampah::hapusJadwalAmbil($idjadwal);

        return ResponseController::sendSuccess(['message' => 'Jadwal pengambilan sampah berhasil dihapus']); 
    }
}