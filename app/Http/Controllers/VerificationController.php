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
use App\Otp;
use App\UserAlamat;
use App\UserLevel;
use App\Daerah;



class VerificationController extends Controller{

    public function getUserAdminDaerah(Request $request){
        $statusverifikasi   = $request->statusverifikasi; // 0 belum verified, 1 verified
        $keyword            = $request->keyword;
        return ResponseController::sendSuccess([
            'message' => 'Data admin daerah', 
            'datauser' => User::searchUser(UserLevel::kodeLevel()['admindaerah'], $statusverifikasi, $keyword)
        ]); 
    }

    public function getUserKurir(Request $request){
        $statusverifikasi   = $request->statusverifikasi; // 0 belum verified, 1 verified
        $keyword            = $request->keyword;
        return ResponseController::sendSuccess([
            'message' => 'Data Kurir', 
            'datauser' => User::searchUser(UserLevel::kodeLevel()['kurir'], $statusverifikasi, $keyword)
        ]); 
    }

    public function getMitra(Request $request){
        $iduser             = $request->iduser;
        $statusverifikasi   = $request->statusverifikasi; // 0 belum verified, 1 verified
        $keyword            = $request->keyword;

        $user = User::find($iduser);
        return ResponseController::sendSuccess([
            'message' => 'Data Kurir', 
            'datauser' => User::searchUser(UserLevel::kodeLevel()['mitra'], $statusverifikasi, $keyword, $user->iddaerah)
        ]); 
    }

    public function getUserResiker(Request $request){
        $statusverifikasi   = $request->statusverifikasi; // 0 belum verified, 1 verified
        $keyword            = $request->keyword;
        return ResponseController::sendSuccess([
            'message' => 'Data Resiker', 
            'datauser' => User::searchUser(UserLevel::kodeLevel()['userumum'], $statusverifikasi, $keyword)
        ]); 
    }


    public function approveVerification(Request $request){
        $iduser   = $request->iduseradmin;
        $status   = $request->status;// 1 approved, -1 tolak

        User::approveUserVerification($iduser, $status);

        $user = User::find($iduser);
        if($status == 1){
            GlobalHelperController::sendEmailVerified($user->email, $user->nama);
        }else{
            
        }

        return ResponseController::sendSuccess([
            'message' => 'User berhasil di'.($status == "1" ? "terima" : "tolak")
        ]); 
    }
}