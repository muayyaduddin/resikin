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
use App\Daerah;


class DaerahController extends Controller{

    public function submitDaerah(Request $request){
        $iddaerah       = $request->iddaerah;
        $nama           = $request->nama;

        if($nama == ""){
            return ResponseController::sendError(5); 
        }
        
        if (empty($iddaerah)){
            //create new
            $namaExist = Daerah::where([ ["nama", $nama], ["status", 1] ])->first();

            if($namaExist){
                return ResponseController::sendError(15);  
            }

            $newDaerah = Daerah::createDaerah($nama);
            
        }else{
            //update
            Daerah::updateDaerah($iddaerah, $nama);
        }
        return ResponseController::sendSuccess(['message' => 'Daerah berhasil '.(empty($iddaerah) ? 'didaftarkan.' : 'diubah')]); 
    }


    public function hapusDaerah(Request $request){
        $iddaerah      = $request->iddaerah;

        if($iddaerah == ""){
            return ResponseController::sendError(5); 
        }

        Daerah::hapusDaerah($iddaerah);

        return ResponseController::sendSuccess(['message' => 'Daerah berhasil dihapus']); 
    }

    public function getDaerah(Request $request){
        $keyword       = $request->keyword;

        if($keyword == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Data daerah resiker.', 
            'data' => Daerah::searchDaerah($keyword)
        ]); 
    }

}