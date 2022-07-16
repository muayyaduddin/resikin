<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class TabunganUser extends Model
{
    protected $table = 'tabunganuser';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id','iduser', 'idtabungan', 'total', 'tanggal'
    ];

    public static function createTabunganResiker($idtabungan, $iduser, $nominal){
        $tabungan = new TabunganUser;
 
        $tabungan->idtabungan = $idtabungan;
        $tabungan->iduser = $iduser;
        $tabungan->total = $nominal;
        $tabungan->tanggal = new DateTime();
        $tabungan->save();
        
        return $tabungan->id;
    }

    static function getLaporantabungan($iddaerah, $idtabungan){
        $filter = "1=1";
        if(!empty($iddaerah)){
            $filter .= " and us.iddaerah = ".$iddaerah;
        }
        if(!empty($idtabungan)){
            $filter .= " and t.id = ".$idtabungan;
        }

        $sql = sprintf(
                        'SELECT tu.id, tu.total, tu.tanggal, u.nama, us.nama as namaalamat, us.iddaerah, t.nama as namatabungan
                        from tabunganuser tu
                        left join user u on tu.iduser = u.id
                        join user_alamat us on u.id = us.iduser and us.isdefault = 1
                        left join tabungan t on tu.idtabungan = t.id
                        WHERE  %s
                        ',
                        $filter
                    );
        return DB::select(DB::raw($sql));
    }
}
