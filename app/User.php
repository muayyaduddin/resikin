<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'idlevel', 'iddaerah', 'nama', 'email', 'password', 'nohp', 'status_verifikasi', 'fcmtoken', 'deviceid'
    ];

    public static function checkUserLogin($email, $password){
        $user = new User;
        return $user->where([ ["email", $email], ["password", $password]])->first();
    }

    public static function getUser($iduser){
        $user = new User;
        return $user->select(DB::raw('user.id, user.idlevel, user.iddaerah, daerah.nama as namadaerah, user.nama, user.email, user.nohp, user.status_verifikasi'))
                ->leftJoin('daerah', 'daerah.id', '=', 'user.iddaerah')
                ->where([ ["user.id", $iduser] ])->first();
    }

    public static function searchUser($kodelevel, $status_verifikasi, $filter, $iddaerah){
        $user = new User;
        return $user->leftJoin('daerah', 'daerah.id', '=', 'user.iddaerah')
        ->leftJoin('user_level', 'user_level.id', '=', 'user.idlevel')
        ->select(DB::raw('user.id, user.idlevel, user.iddaerah, daerah.nama as namadaerah, user.nama, user.email, user.nohp, user.status_verifikasi, IF(user.status_verifikasi = 0, "Belum Verifikasi", "Sudah verifikasi") as namastatus'))
        ->where([ 
            ["user_level.kode", $kodelevel], 
            ["user.status_verifikasi", $status_verifikasi], 
            ["user.nama", 'like', '%'.$filter.'%'],
            ['user.iddaerah', $iddaerah] ])
        ->orWhere([ 
            ["user_level.kode", $kodelevel], 
            ["user.status_verifikasi", $status_verifikasi], 
            ["user.email", 'like', '%'.$filter.'%'],
            ['user.iddaerah', $iddaerah] ])
        ->orWhere([ 
            ["daerah.nama", $kodelevel], 
            ["user.status_verifikasi", $status_verifikasi], 
            ["daerah.nama", 'like', '%'.$filter.'%'],
            ['user.iddaerah', $iddaerah] ])
        ->get();
    }

    public static function createUser($nama, $email, $nohp, $password, $idlevel, $iddaerah){
        $usr = new User;
 
        $usr->nama = $nama;
        $usr->email = $email;
        $usr->password = $password;
        $usr->nohp = $nohp;
        $usr->status_verifikasi = "0";
        $usr->idlevel = $idlevel;
        $usr->iddaerah = $iddaerah;
        $usr->save();
        
        return $usr->id;
    }

    static function approveUserVerification($iduser, $status){
        $usr = User::find($iduser);
        $usr->status_verifikasi = $status;
        $usr->save();
    }

    static function getAvailableDrivers($idjadwal){
        $sql = sprintf(
                        'SELECT u.id, deviceid, u.nama, fcmtoken, IFNULL(ok.jumlahorder, 0) as jumlahorderaktif
                        from user u
                        left join (Select p.id, p.tanggal, p.idjadwal, p.idkurir, count(p.idkurir) as jumlahorder, p.status
                            from penjualan p
                            left join progress_penjualan pp on p.id = pp.idpenjualan 
                            where idkurir is not null and DATE(p.tanggal) = DATE(NOW()) and p.statusprogress <> 2 and idjadwal = %s
                            group by p.idkurir) as ok on u.id = ok.idkurir
                        where idlevel = 3 
                        having jumlahorderaktif < 10',
                        $idjadwal
                    );
        return DB::select(DB::raw($sql));
    }

    static function getAvailablePembelianDrivers(){
        $sql = sprintf(
                        'SELECT id, deviceid, nama, fcmtoken
                        from 
                        user where idlevel = 3 and id not in (Select idkurir 
                        from pembelian p
                        left join progress_pembelian pp on p.id = pp.idpembelian 
                        where idkurir is not null and statusprogress <> 3)',
                    );
        return DB::select(DB::raw($sql));
    }

}
