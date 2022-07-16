<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoMitra extends Model
{
    protected $table = 'info_mitra';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id','iduser', 'iddaerah', 'nama', 'foto', 'gpsl', 'gpsb', 'alamat'
    ];


    public static function getInfoMitra($iduser){
        $alamat = new InfoMitra;
        return $alamat->where([ ["iduser", $iduser] ])->get();
    }

    public static function createInfo($iduser, $iddaerah, $nama, $foto, $gpsl, $gpsb, $alamat){
        $info = new InfoMitra;
 
        $info->iduser = $iduser;
        $info->iddaerah = $iddaerah;
        $info->nama = $nama;
        $info->foto = $foto;
        $info->gpsl = $gpsl;
        $info->gpsb = $gpsb;
        $info->alamat = $alamat;
        $info->save();
        
        return $info->id;
    }
}
