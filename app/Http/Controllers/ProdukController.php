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
use App\UserLevel;
use App\InfoMitra;
use App\JenisProduk;
use App\Produk;



class ProdukController extends Controller{
    
    public function submitProduk(Request $request){
        $iduser         = $request->iduser;
        $idproduk       = $request->idproduk;
        $idjenisproduk  = $request->idjenisproduk;
        $nama           = $request->nama;
        $fotoname       = $request->fotoname;
        $deskripsi      = $request->deskripsi;
        $harga          = $request->harga;
        $satuan         = $request->satuan;

        //pengecekan required variable 
        if($iduser == "" || $idjenisproduk == "" || $nama == "" || $harga == ""){
            return ResponseController::sendError(5); 
        }

        if(empty($idproduk)){

            if(Produk::isNamaExist($iduser, $nama)){
                return ResponseController::sendError(14); 
            }

            //input produk ke database
            Produk::createProduk($idjenisproduk, $iduser, $nama, $fotoname, $deskripsi, $harga, $satuan);
        }else{
            //update
            Produk::updateProduk($idproduk, $idjenisproduk, $nama, $fotoname, $deskripsi, $harga, $satuan);
        }

        return ResponseController::sendSuccess(['message' => 'Produk berhasil '.(empty($idproduk) ? 'didaftarkan.' : 'diubah')]); 
    }

    public function getListProduk(Request $request){
        $iduser         = $request->iduser;
        $iddaerah       = $request->iddaerah;
        $keyword        = $request->keyword;

        if($iddaerah == ""){
            return ResponseController::sendError(5); 
        }
        
        $user = User::find($iduser);
        if(!UserLevel::isUserMitra($user->idlevel)){
            $iduser = "";
        }

        return ResponseController::sendSuccess([
            'message' => 'Data produk.', 
            'data' => Produk::getListProduk($iddaerah, $keyword, $iduser)
        ]); 
    }

    public function getDetailProduk(Request $request){
        $idproduk       = $request->idproduk;

        if($idproduk == ""){
            return ResponseController::sendError(5); 
        }

        return ResponseController::sendSuccess([
            'message' => 'Detail data produk.', 
            'data' => Produk::getDetailProduk($idproduk)
        ]); 
    }

    public function hapusProduk(Request $request){
        $idproduk       = $request->idproduk;

        if($idproduk == ""){
            return ResponseController::sendError(5); 
        }

        Produk::hapusProduk($idproduk);

        return ResponseController::sendSuccess(['message' => 'Produk berhasil dihapus']); 
    }
}