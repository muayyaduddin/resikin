<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class BarangPenjualan extends Model
{
    protected $table = 'barang_penjualan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'idpenjualan', 'idjenissampah', 'jumlah'
    ];


    static function createBarangPenjualan($idpenjualan, $idjenissampah, $jumlah){
        $info = new BarangPenjualan;
 
        $info->idpenjualan = $idpenjualan;
        $info->idjenissampah = $idjenissampah;
        $info->jumlah = $jumlah;
        $info->save();
        
        return $info->id;
    }
}
