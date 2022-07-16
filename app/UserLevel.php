<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class UserLevel extends Model
{
    protected $table = 'user_level';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'kode', 'nama', 'pendaftarankhusus', 'requestotp'
    ];

    static function kodeLevel(){
        return [
            'adminpusat'    => 'ADP',
            'admindaerah'   => 'ADD',
            'kurir'         => 'KUR',
            'mitra'         => 'MIT',
            'userumum'      => 'PUM',
        ];
    }

    public static function getLevelUmum(){
        return UserLevel::select(DB::raw('id, nama, kode, requestotp'))->where([['pendaftarankhusus', '0']])->get();
    }

    public static function isLevelRequestOtp($id){
        $userlevel = UserLevel::find($id);
        return $userlevel && $userlevel->requestotp == "1" ? true : false;
    }

    public static function isUserPenggunaUmum($idleveluser){
        $userlevel = UserLevel::find($idleveluser);
        return $userlevel && $userlevel->kode == UserLevel::kodeLevel()['userumum'] ? true : false;
    }

    public static function isUserMitra($idleveluser){
        $userlevel = UserLevel::find($idleveluser);
        return $userlevel && $userlevel->kode == UserLevel::kodeLevel()['mitra'] ? true : false;
    }

    public static function isUserAdminDaerah($idleveluser){
        $userlevel = UserLevel::find($idleveluser);
        return $userlevel && $userlevel->kode == UserLevel::kodeLevel()['admindaerah'] ? true : false;
    }


    public static function isLevelPendaftaranKhusus($idleveluser){
        $userlevel = UserLevel::find($idleveluser);
        return $userlevel && $userlevel->pendaftarankhusus == "1" ? true : false;
    }
}
