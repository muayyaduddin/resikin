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



class AlamatResikerController extends Controller{

    public function pendaftaranAlamat(Request $request){
        $iduser         = $request->iduser;
        $idalamat       = $request->idalamat;
        $iddaerah       = $request->iddaerah;
        $nama           = $request->nama;
        $gpsl           = $request->gpsl;
        $gpsb           = $request->gpsb;
        $detail         = $request->alamat;

        if($iduser == "" || $nama == "" || $detail == ""){
            return ResponseController::sendError(5); 
        }
        
        if (empty($idalamat)){
            //create new
            $namaExist = UserAlamat::where([ ["nama", $nama], ['iduser', $iduser] ])->first();

            if($namaExist){
                return ResponseController::sendError(10);  
            }

            $newAlamat = UserAlamat::createAlamat($iduser, $iddaerah, $nama, $gpsl, $gpsb, $detail, sizeof(UserAlamat::getAlamatUser($iduser)) == 0 ? "1" : "0");
            
        }else{
            //update
            UserAlamat::updateAlamat($idalamat, $iddaerah, $nama, $gpsl, $gpsb, $detail);
        }
        return ResponseController::sendSuccess(['message' => 'Alamat berhasil '.(empty($idalamat) ? 'didaftarkan.' : 'diubah')]); 

    }

    public function getListDaerah(Request $request){

        return ResponseController::sendSuccess([
            'message' => 'Data list daerah.', 
            'data' => Daerah::get()
        ]); 
    }

    public function getAlamatDefault(Request $request){
        $iduser         = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Detail data produk.', 
            'data' => UserAlamat::getDefault($iduser)
        ]); 
    }

    public function getAlamatResiker(Request $request){
        $iduser        = $request->iduser;
        $keyword       = $request->keyword;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Data alamat resiker.', 
            'data' => UserAlamat::searchAlamat($iduser, $keyword)
        ]); 
    }

    public function hapusAlamat(Request $request){
        $idalamat      = $request->idalamat;

        if($idalamat == ""){
            return ResponseController::sendError(5); 
        }

        UserAlamat::hapusAlamat($idalamat);

        return ResponseController::sendSuccess(['message' => 'Alamat berhasil dihapus']); 
    }
}