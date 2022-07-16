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
use App\InfoMitra;



class AuthController extends Controller{

    public function getFormPendaftaranData(Request $request){
        return ResponseController::sendSuccess([
            'message' => 'Pendaftaran berhasil, mohon cek email untuk melakukan verifikasi akun.', 
            'datalevel' => UserLevel::getLevelUmum(), 
            'datadaerah' => Daerah::get()
        ]); 
    }

  	public function pendaftaranUser(Request $request){
        $nama           = $request->nama;
        $email          = $request->email;
        $nohp          = $request->nohp;
        $password       = $request->password;
        $idlevel       = isset($request->idlevel) ? $request->idlevel : "2";
        $iddaerah       = $request->iddaerah;

        if($email == "" || $nama == "" || $password == "" || $nohp == ""){
            return ResponseController::sendError(5); 
        }
        
        $userExist = User::where([ ["email", $email] ])->first();

        if($userExist){
            return ResponseController::sendError(6);  
        }

        $newuser = User::createUser($nama, $email, $nohp, $password, $idlevel, $iddaerah);

        $openOTP = false;
        $message = "Pendaftaran berhasil, harap tunggu verifikasi admin dalam kurun waktu 1x24 jam.";
        if(UserLevel::isLevelRequestOtp($idlevel)){
            $openOTP = true;
            $message = 'Pendaftaran berhasil, mohon cek email untuk melakukan verifikasi akun.';
            $this->createNewOtp($newuser, $email, $nama);
        }

        return ResponseController::sendSuccess([
            'message' => $message, 
            'iduser' => $newuser, 
            'openotp' => $openOTP
        ]); 
  	}

    public function createNewOtp($iduser, $email, $nama){
         $otp = rand(1111,9999);
         Otp::createOTP($iduser, $otp);
         GlobalHelperController::sendOTPMail($email, $nama, $otp);
    }

    public function checkOtp(Request $request){
        $iduser    = $request->iduser;
        $otp       = $request->otp;

        $checkotp = Otp::isOTPCorrect($iduser, $otp);
        if($checkotp){
            User::approveUserVerification($iduser, "1");
            return ResponseController::sendSuccess(['message' => 'Akun terverifikasi, silahkan login menggunakan akun anda.']);
        }
        return ResponseController::sendError(7); 
    }

    public function login(Request $request){
        $email          = $request->email;
        $password       = $request->password;

        $userLogin = User::checkUserLogin($email, $password);

        if($userLogin){
            $isOpenOTP = false;
            if($userLogin->status_verifikasi == 0){
                if(!UserLevel::isLevelRequestOtp($userLogin->idlevel)){
                    return ResponseController::sendError(11);  
                }else{
                  $isOpenOTP = true;
                    $this->createNewOtp($userLogin->id, $userLogin->email, $userLogin->nama);
                }
            }

            return ResponseController::sendSuccess([
                'iduser' => $userLogin->id,
                'nama' => $userLogin->nama,
                'email' => $userLogin->email,
                'openotp' => $isOpenOTP ,
            ]);  
        }

        return ResponseController::sendError(8);  
    }

    public function accessCheck(Request $request){
        $iduser          = $request->iduser;

        $userLogin = User::getUser($iduser);

        if($userLogin){
            $userLogin->fcmtoken = $request->tokenfirebase;
            $userLogin->deviceid = $request->imei;
            $userLogin->save();

            //jika level TIDAK BUTUH request otp dan status verifikasi user 0 
            //maka diarahkan ke login
            if(!UserLevel::isLevelRequestOtp($userLogin->idlevel) && $userLogin->status_verifikasi == 0){
                return ResponseController::sendError(9);  
            }

            //jika level butuh request otp dan status verifikasi user 0 
            //maka buat otp dan send code buka halaman otp untuk aplikasi
            $isOpenOTP = false;
            if(UserLevel::isLevelRequestOtp($userLogin->idlevel) && $userLogin->status_verifikasi == 0){
                $isOpenOTP = true;
                $this->createNewOtp($userLogin->id, $userLogin->email, $userLogin->nama);
            }

            //jika user umum maka check ketersediaan alamat
            $isOpenAlamatForm = false;
            if(UserLevel::isUserPenggunaUmum($userLogin->idlevel) && sizeof(UserAlamat::getAlamatUser($iduser)) == 0){
                $isOpenAlamatForm = true;
            }

            //jika user mitra maka cek ketersediaan info mitra
            $isOpenFormInfoMitra = false;
            if(UserLevel::isUserMitra($userLogin->idlevel) && sizeof(InfoMitra::getInfoMitra($iduser)) == 0){
                $isOpenFormInfoMitra = true;
            }

            return ResponseController::sendSuccess([
                'openotp' => $isOpenOTP ,
                'openformalamat' =>  $isOpenAlamatForm ? '1' : '-1',
                'openformmitra' => $isOpenFormInfoMitra ? '1' : '-1',
                'user' => $userLogin
            ]);  
        }

        return ResponseController::sendError(9);  
    }
    
    public function userprofil(Request $request){
        $iduser          = $request->iduser;

        if($iduser == ""){
            return ResponseController::sendError(5); 
        }

        $user = User::getUser($iduser);

        $alamatdefault = "";
        if(UserLevel::isUserPenggunaUmum($user->idlevel)){
            $alamatdefault = UserAlamat::getDefault($iduser);
        }

        return ResponseController::sendSuccess([
            'user' => $user,
            'alamatdefault' => $alamatdefault
        ]);  
    }
}