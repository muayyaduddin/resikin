<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Daerah extends Model
{
    protected $table = 'daerah';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'nama', 'status'
    ];

    public static function createDaerah($nama){
        $daerah = new Daerah;
 
        $daerah->nama = $nama;
        $daerah->status = 1;
        $daerah->save();
        
        return $daerah->id;
    }

    static function searchDaerah($keyword){
        $sql = sprintf(
                        'SELECT *
                        from daerah
                        where 
                        nama like "%%%s%%" and status <> -1',
                        $keyword
                    );
        return DB::select(DB::raw($sql));
    }

    static function updateDaerah($id, $nama){
        $ud = Daerah::find($id);
        $ud->nama = $nama;
        $ud->save();
    }

    static function hapusDaerah($id){
        $alamat = Daerah::find($id);
        $alamat->status = -1;
        $alamat->save();
    }

}
