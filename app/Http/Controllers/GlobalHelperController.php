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
// use Kutia\Larafirebase\Facades\Larafirebase;
use App\User;
use Milon\Barcode\Facades\DNS2DFacade;
use App\PenjualanSampah;
use App\Pembelian;
use App\Tabungan;
use App\Sedekah;


class GlobalHelperController extends Controller
{

    private static $nama;
    protected static $mail;
    protected static $otp;


    public static function sendOTPMail($email, $nama, $otp)
    {
        static::$nama = $nama;
        static::$mail = $email;
        static::$otp = $otp;

        $data = array('name' => $nama, "otp" => $otp);

        Mail::send('mail', $data, function ($message) {
            $message->to(static::$mail, static::$nama)->subject('OTP Resikin');
            $message->from('support@resikin.com', 'RESIKIN');
        });
    }

    public static function sendEmailVerified($email, $nama)
    {
        static::$nama = $nama;
        static::$mail = $email;

        $data = array('name' => $nama);

        Mail::send('mailverifiedaccount', $data, function ($message) {
            $message->to(static::$mail, static::$nama)->subject('Verifikasi Akun Resikin');
            $message->from('support@resikin.com', 'RESIKIN');
        });
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'uploaded_file' => 'required',
            'uploaded_file.*' => 'image|mimes:jpeg,png,jpg|max:5048'
        ]);


        if ($request->hasfile('uploaded_file')) {
            $file = $request->file('uploaded_file');
            $file->move('uploads/', $request->file('uploaded_file')->getClientOriginalName());
        }

        return ResponseController::sendSuccess(['message' => 'Foto terkirim']);
    }

    public function generateQRCode(Request $request)
    {
        $file = $this->createQRCode("5-cobaqrcode");
        return $file;
    }

    public static function createQRCode($qrvalue)
    {
        return DNS2DFacade::getBarcodeHTML($qrvalue, 'QRCODE');
    }

    public static function pushNotification($token, $title, $message, $idtambahan = 0)
    {
        $response = Http::post('https://us-central1-numeric-anthem-236903.cloudfunctions.net/sendNotif', [
            "token" => $token,
            "message" => $message,
            "idtambahan" => $idtambahan
        ]);
    }

    public static function getDriversDistance($userlat, $userlon)
    {
        $response = Http::acceptJson()->post('https://us-central1-numeric-anthem-236903.cloudfunctions.net/nearestgps', [
            "lat" => $userlat,
            "lon" => $userlon
        ]);

        return $response->body();
    }

    public static function getUserSaldo($iduser)
    {
        $totalPenjualan = PenjualanSampah::getTotalPenjualanResiker($iduser);
        $totalPembelian = Pembelian::getTotalPembelianResiker($iduser);
        $totaltabungan  = Tabungan::getTotalTabunganResiker($iduser);
        $totalsedekah  = Sedekah::getTotalSedekahResiker($iduser);
        return $totalPenjualan - $totalPembelian - $totaltabungan - $totalsedekah;
    }
}
