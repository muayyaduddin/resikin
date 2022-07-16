<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalAmbilSampah extends Model
{
    protected $table = 'jadwal_ambil_sampah';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'iddaerah', 'jam'
    ];

    static function checkJamExist($iddaerah, $jam){
        $jw = new JadwalAmbilSampah;
        return $jw->where([ ["iddaerah", $iddaerah], ["jam", $jam] ])->first() ? true : false;
    }

    static function getListJam($iddaerah){
        return JadwalAmbilSampah::where([ ["iddaerah", $iddaerah] ])->get();
    }

    static function createJam($iddaerah, $jam){
        $jw = new JadwalAmbilSampah;
 
        $jw->jam = $jam;
        $jw->iddaerah = $iddaerah;
        $jw->save();
        
        return $jw->id;
    }

    static function updateJadwalAmbil($id, $jam){
        $jw = JadwalAmbilSampah::find($id);
        $jw->jam = $jam;
        $jw->save();
    }

    static function hapusJadwalAmbil($id){
        $jw = JadwalAmbilSampah::find($id);
        $jw->delete();
    }
}
