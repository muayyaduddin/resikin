<?php

namespace App\Http\Controllers;

class ResponseController {

	public static function sendSuccess($data){
        $res['status']  = '00';
        $res['message'] = isset($data['message']) ? $data['message'] : 'Sukses';
        $res = array_merge($res, $data);
        return json_encode($res, true); 
    }

    public static function sendError($code){
        $res['status']  = $code;
        $res['message'] = ResponseController::getErrorMessage($code);
        return json_encode($res, true); 
    }

    public static function sendErrorData($code, $data){
        $res['status']  = $code;
        $res['message'] = ResponseController::getErrorMessage($code);
        $res['data'] = $data;
        return json_encode($res, true); 
    }

    public static function getErrorMessage($code){
        switch ($code) {
            case 5:
                return 'Format request salah.';
            case 6:
                return "Email sudah terdaftar.\nMohon gunakan email lain atau gunakan fitur lupa password.";
            case 7:
                return "OTP tidak sesuai.\nmohon check kembali OTP terakhir yang dikirim ke email.";
            case 8:
                return 'Email atau password salah.';
            case 9:
                return "Harap login ulang.";
            case 10:
                return "Nama alamat sudah digunakan,\nharap memakai nama lain.";
            case 11:
                return "Akun anda belum diverifikasi,\nharap tunggu dalam kurun waktu 1x24 jam.";
            case 12:
                return "Akun anda belum diverifikasi,\nharap verifikasi kode otp yang kami kirimkan ke email";
            case 13:
                return "Nama jenis sudah terdaftar,\nmohon gunakan nama lain.";
            case 14:
                return "Nama produk sudah terdaftar,\nmohon gunakan nama lain.";
            case 15:
                return "Nama daerah sudah terdaftar,\nmohon gunakan nama lain.";
            case 16:
                return "Kurir sedang tidak tersedia, mohon ulangi beberapa saat lagi.";
            case 17:
                return "Saldo anda tidak mencukupi,\nharap lakukan penjualan sampah terlebih dahulu.";
            default:
                return 'Error Unknown';
        }
    }
}