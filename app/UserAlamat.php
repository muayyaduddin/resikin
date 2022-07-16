<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserAlamat extends Model
{
    protected $table = 'user_alamat';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id','iduser', 'iddaerah', 'nama', 'gpsl', 'gpsb', 'detail', 'isdefault', 'status'
    ];


    public static function getAlamatUser($iduser){
        $alamat = new UserAlamat;
        return $alamat->where([ ["iduser", $iduser] ])-> get();
    }

    public static function createAlamat($iduser, $iddaerah, $nama, $gpsl, $gpsb, $detail, $isdefault){
        $alamat = new UserAlamat;
 
        $alamat->iduser = $iduser;
        $alamat->iddaerah = $iddaerah;
        $alamat->nama = $nama;
        $alamat->gpsl = $gpsl;
        $alamat->gpsb = $gpsb;
        $alamat->detail = $detail;
        $alamat->isdefault = $isdefault;
        $alamat->status = 1;
        $alamat->save();
        
        return $alamat->id;
    }

    static function searchAlamat($iduser, $keyword){
        $sql = sprintf(
                        'SELECT a.* , b.nama as daerah
                        from user_alamat as a
                        left join daerah as b on a.iddaerah = b.id
                        where 
                        a.iduser = %s and (a.nama like "%%%s%%" or a.detail like "%%%s%%") and a.status <> -1',
                        $iduser,
                        $keyword,
                        $keyword
                    );
        return DB::select(DB::raw($sql));
    }

    static function getDefault($iduser){
        $sql = sprintf(
                        'SELECT a.id, a.iddaerah, a.nama, a.detail , b.nama as daerah
                        from user_alamat as a
                        left join daerah as b on a.iddaerah = b.id
                        where 
                        a.iduser = %s and a.isdefault = 1 and a.status <> -1
                        limit 1',
                        $iduser
                    );
        $result = DB::select(DB::raw($sql));
        return $result && sizeof($result) == 1 ? $result[0] : null;
    }

    static function updateAlamat($id, $iddaerah, $nama, $gpsl, $gpsb, $detail){
        $jp = UserAlamat::find($id);
        $jp->iddaerah = $iddaerah;
        $jp->nama = $nama; 
        $jp->gpsl = $gpsl;
        $jp->gpsb = $gpsb; 
        $jp->detail = $detail;
        $jp->save();
    }

    static function hapusAlamat($id){
        $alamat = UserAlamat::find($id);
        $alamat->status = -1;
        $alamat->save();
    }
}
