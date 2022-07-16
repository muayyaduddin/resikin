<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JenisProduk extends Model
{
    protected $table = 'jenis_produk';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'iddaerah', 'nama', 'status'
    ];

    static function checkNamaExist($iddaerah, $nama){
        $jp = new JenisProduk;
        return $jp->where([ ["iddaerah", $iddaerah], ["nama", $nama], ["status", '1'] ])->first() ? true : false;
    }

    static function getListJenisProduk($iddaerah){
        return JenisProduk::where([ ["iddaerah", $iddaerah], ["status", '1'] ])->get();
    }

    static function createJenisProduk($iddaerah, $nama){
        $jp = new JenisProduk;
 
        $jp->nama = $nama;
        $jp->iddaerah = $iddaerah;
        $jp->status = 1;
        $jp->save();
        
        return $jp->id;
    }

    static function updateJenisProduk($id, $nama){
        $jp = JenisProduk::find($id);
        $jp->nama = $nama;
        $jp->save();
    }

    static function hapusJenisProduk($id){
        $jp = JenisProduk::find($id);
        $jp->status = -1;
        $jp->save();
    }
}
