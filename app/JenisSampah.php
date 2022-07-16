<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisSampah extends Model
{
    protected $table = 'jenis_sampah';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'iddaerah', 'nama', 'harga', 'satuan', 'status'
    ];
    

    static function checkNamaExist($iddaerah, $nama){
        $jp = new JenisSampah;
        return $jp->where([ ["iddaerah", $iddaerah], ["nama", $nama], ["status", '1'] ])->first() ? true : false;
    }

    static function getListJenisSampah($iddaerah){
        return JenisSampah::where([ ["iddaerah", $iddaerah], ["status", '1'] ])->get();
    }

    static function createJenisSampah($iddaerah, $nama, $harga, $satuan){
        $js = new JenisSampah;
 
        $js->nama = $nama;
        $js->iddaerah = $iddaerah;
        $js->status = 1;
        $js->harga = $harga;
        $js->satuan = $satuan;
        $js->save();
        
        return $js->id;
    }

    static function updateJenisSampah($id, $nama, $harga, $satuan){
        $js = JenisSampah::find($id);
        $js->nama = $nama;
        $js->harga = $harga;
        $js->satuan = $satuan;
        $js->save();
    }

    static function hapusJenisSampah($id){
        $js = JenisSampah::find($id);
        $js->status = -1;
        $js->save();
    }


    static function getHargaJenis($idjenissampah, $jumlahpenjualan){
        $sampah = JenisSampah::find($idjenissampah);
        return $sampah->harga * $jumlahpenjualan;
    }
}
