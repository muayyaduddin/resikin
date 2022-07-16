<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Otp extends Model
{
    protected $table = 'otp';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id','iduser', 'otp', 'validuntil', 'expired'
    ];

    public static function createOTP($iduser, $kodeotp){
        $otp = new Otp;
 
        $otp->iduser = $iduser;
        $otp->otp = $kodeotp;
        $otp->validuntil = new DateTime();
        $otp->save();
        
        return $otp->id;
    }

    public static function isOTPCorrect($iduser, $otp){
        $sql =  sprintf("SELECT  *          
                    from otp 
                    where iduser = %s and otp = %s and expired = 0;", 
                    $iduser,
                    $otp
                );
        $dataotp = DB::select(DB::raw($sql));
        return sizeof($dataotp) >= 1 ? true : false;
    }
}
