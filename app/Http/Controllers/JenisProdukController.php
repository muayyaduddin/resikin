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
use App\JenisProduk;



class JenisProdukController extends Controller{
  	public function submitJenisProduk(Request $request){
        $iduser         = $request->iduser;
        $idjenisproduk  = $request->idjenisproduk;
        $iddaerah       = $request->iddaerah;
        $nama           = $request->nama;

        if($iddaerah == "" || $nama == ""){
            return ResponseController::sendError(5); 
        }

        if(empty($idjenisproduk)){
            //createnew
            if(JenisProduk::checkNamaExist($iddaerah, $nama)){
                return ResponseController::sendError(13); 
            }
            $newjp = JenisProduk::createJenisProduk($iddaerah, $nama);
        }else{
            //update
            JenisProduk::updateJenisProduk($idjenisproduk, $nama);
        }

        return ResponseController::sendSuccess(['message' => 'Jenis produk berhasil '.(empty($idjenisproduk) ? 'didaftarkan.' : 'diubah')]); 
  	}

    public function getDataJenisProduk(Request $request){
        $iduser         = $request->iduser;
        $iddaerah       = $request->iddaerah;

        if($iddaerah == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Data jenis produk.', 
            'data' => JenisProduk::getListJenisProduk($iddaerah)
        ]); 
    }

    public function hapusJenisProduk(Request $request){
        $iduser         = $request->iduser;
        $idjenisproduk  = $request->idjenisproduk;

        if($idjenisproduk == "" || $iduser == ""){
            return ResponseController::sendError(5); 
        }

        JenisProduk::hapusJenisProduk($idjenisproduk);

        return ResponseController::sendSuccess(['message' => 'Jenis produk berhasil dihapus']); 
    }
}