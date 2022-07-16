<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class SedekahUser extends Model
{
    protected $table = 'sedekahuser';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id','iduser', 'idsedekah', 'total', 'tanggal'
    ];


    public static function createSedekahResiker($idsedekah, $iduser, $nominal){
        $sedekah = new SedekahUser;
 
        $sedekah->idsedekah = $idsedekah;
        $sedekah->iduser = $iduser;
        $sedekah->total = $nominal;
        $sedekah->tanggal = new DateTime();
        $sedekah->save();
        
        return $sedekah->id;
    }

    static function getLaporansedekah($iddaerah){
        $filter = "";
        if(!empty($iddaerah)){
            $filter = "WHERE us.iddaerah = ".$iddaerah;
        }

        $sql = sprintf(
                        'SELECT su.id, su.total, su.tanggal, u.nama, us.nama as namaalamat, us.iddaerah, s.nama as namasedekah 
                            from sedekahuser su
                            left join user u on su.iduser = u.id
                            join user_alamat us on u.id = us.iduser and us.isdefault = 1
                            left join sedekah s on su.idsedekah = s.id
                        %s
                        ',
                        $filter
                    );
        return DB::select(DB::raw($sql));
    }
}
