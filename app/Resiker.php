<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Resiker extends Model
{
    // protected $table = 'penjualan';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $fillable = [
    //     'id','idalamat', 'iduser', 'foto', 'totalpenjualan', 'idjadwal', 'tanggal', 'status', 'statusprogress'
    // ]; 

    static function produkTerbaru($iddaerah){
        $sql = sprintf(
                        'SELECT a.id, a.nama, b.nama as jenis_barang, d.nama as mitra, a.foto, a.harga, a.satuan, d.iddaerah
                        FROM produk as a
                        LEFT JOIN jenis_produk as b ON a.idjenis = b.id
                        LEFT JOIN user as c ON a.iduser = c.id
                        LEFT JOIN info_mitra as d ON d.iduser = c.id
                        WHERE a.status = 1 and d.iddaerah = %s ORDER BY a.id DESC
                        LIMIT 6',
                        $iddaerah,
                    );
        return DB::select(DB::raw($sql));
    }

}
