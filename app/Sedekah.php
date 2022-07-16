<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Sedekah extends Model
{
    protected $table = 'sedekah';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'iddaerah', 'nama', 'deskripsi', 'status'
    ];


    public static function createSedekah($nama, $deskripsi, $iddaerah){
        $tabungan = new Sedekah;
 
        $tabungan->nama = $nama;
        $tabungan->deskripsi = $deskripsi;
        $tabungan->iddaerah = $iddaerah;
        $tabungan->save();
        
        return $tabungan->id;
    }

    static function updateSedekah($id, $nama, $deskripsi){
        $tb = Sedekah::find($id);
        $tb->nama = $nama;
        $tb->deskripsi = $deskripsi;
        $tb->save();
    }

    static function hapusSedekah($id){
        $tb = Sedekah::find($id);
        $tb->status = -1;
        $tb->save();
    }

    static function getTotalSedekahResiker($iduser){
        $sql = sprintf(
                        'SELECT id, sum(total) as totaltabungan from sedekahuser where iduser = %s',
                        $iduser,
                    );
        $total = DB::select(DB::raw($sql));
        return sizeof($total) > 0 ? ($total[0]->totaltabungan ? $total[0]->totaltabungan  : 0) : 0;
    }

}
