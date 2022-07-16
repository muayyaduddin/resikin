<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Tabungan extends Model
{
    protected $table = 'tabungan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'iddaerah', 'nama', 'deskripsi', 'status'
    ];


    public static function createTabungan($nama, $deskripsi, $iddaerah){
        $tabungan = new Tabungan;
 
        $tabungan->nama = $nama;
        $tabungan->deskripsi = $deskripsi;
        $tabungan->iddaerah = $iddaerah;
        $tabungan->save();
        
        return $tabungan->id;
    }

    static function updateTabungan($id, $nama, $deskripsi){
        $tb = Tabungan::find($id);
        $tb->nama = $nama;
        $tb->deskripsi = $deskripsi;
        $tb->save();
    }

    static function hapusTabungan($id){
        $tb = Tabungan::find($id);
        $tb->status = -1;
        $tb->save();
    }


    static function getTabunganByUser($iduser){
        $sql = sprintf(
                        'SELECT a.*, IFNULL(b.totaltabungan, 0) as totaltabungan
                        from tabungan as a
                        left join (select idtabungan, sum(total) as totaltabungan
                        from tabunganuser where iduser = %s) as b on a.id = b.idtabungan',
                        $iduser,
                    );
        return DB::select(DB::raw($sql));
    }

    static function getTotalTabunganResiker($iduser){
        $sql = sprintf(
                        'SELECT idtabungan, sum(total) as totaltabungan from tabunganuser where iduser = %s',
                        $iduser,
                    );
        $total = DB::select(DB::raw($sql));
        return sizeof($total) > 0 ? ($total[0]->totaltabungan ? $total[0]->totaltabungan  : 0) : 0;
    }
}
