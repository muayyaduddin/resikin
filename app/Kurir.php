<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Kurir extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id','idalamat', 'iduser', 'foto', 'totalpenjualan', 'idjadwal', 'tanggal', 'status', 'statusprogress'
    ];


    static function updateProgress($id, $foto){
        $up = Kurir::find($id);
        $stts = "1";
        if($up->statusprogress != 0){
            $stts = "2";
        }
        $up->statusprogress = $stts;
        $up->save();
        
        return $stts;
    }

    static function dataAktifOrder($iduser){
        $sql = sprintf(
                        '(SELECT a.id, a.tanggal, b.nama as nama, c.jam as jam, d.detail as alamat, e.nama as daerah, IF(a.statusprogress = 0, "Menunggu Penjemputan", "Menuju Resiker") as progress_kurir, "Penjualan Sampah" as title, 1 as jenistransaksi
                        FROM penjualan as a
                        LEFT JOIN user as b ON a.iduser = b.id
                        LEFT JOIN jadwal_ambil_sampah as c ON a.idjadwal = c.id
                        LEFT JOIN user_alamat as d ON a.idalamat = d.id
                        LEFT JOIN daerah as e ON d.iddaerah = e.id
                        WHERE a.idkurir = %s and a.statusprogress <> 2 and a.status <> -1
                        )
                        UNION ALL
                        (
                        SELECT a.id, a.tanggal, b.nama as nama, "0" as jam, d.detail as alamat, e.nama as daerah, IF(a.statusprogress = 0, "Menunggu response", IF(a.statusprogress = 1, "Menuju toko", IF(a.statusprogress = 2, "Dalam perjalanan", "Selesai"))) as progress_kurir, "Pembelian Produk" as title, 2 as jenistransaksi
                        FROM pembelian as a
                        LEFT JOIN user as b ON a.iduser = b.id
                        LEFT JOIN user_alamat as d ON a.idalamat = d.id
                        LEFT JOIN daerah as e ON d.iddaerah = e.id
                        WHERE a.idkurir = %s and a.statusprogress <> 3 and a.status <> -1
                        )',
                        $iduser,
                        $iduser,
                    );
        return DB::select(DB::raw($sql));
    }


    static function historyWorkOrder($iduser){
        $sql = sprintf(
            'SELECT a.id, a.tanggal, b.nama as nama, c.jam as jam, d.detail as alamat, e.nama as daerah, IF(a.statusprogress = 0, "Menunggu Penjemputan", IF(a.statusprogress = 1, "Dalam perjalanan" , "Selesai")) as progress_kurir
            FROM penjualan as a
            LEFT JOIN user as b ON a.iduser = b.id
            LEFT JOIN jadwal_ambil_sampah as c ON a.idjadwal = c.id
            LEFT JOIN user_alamat as d ON a.idalamat = d.id
            LEFT JOIN daerah as e ON d.iddaerah = e.id
            WHERE a.idkurir = %s and a.status <> -1',
            $iduser,
        );
        return DB::select(DB::raw($sql));
    }


    static function detailWorkOrder($idpenjualan){
        $sql = sprintf(
                        'SELECT a.id, a.tanggal, b.nama as nama, b.detail AS detailalamat, e.nama as daerah, c.jam AS jadwal, b.nama as resiker, b.nohp, IF(a.statusprogress = 0, "Menunggu Penjemputan", IF(a.statusprogress = 1, "Menuju Resiker" , "Selesai")) as progress_kurir, a.statusprogress as status
                        FROM penjualan as a
                        LEFT JOIN user as b ON a.iduser = b.id
                        LEFT JOIN jadwal_ambil_sampah as c ON a.idjadwal = c.id
                        LEFT JOIN user_alamat as d ON a.idalamat = d.id
                        LEFT JOIN daerah as e ON d.iddaerah = e.id
                        WHERE a.id = %s and a.status <> -1
                        LIMIT 1',
                        $idpenjualan,
                    );
        return DB::select(DB::raw($sql));
    }

    static function getDetailBarang($idpenjualan){
        $sql = sprintf(
                        'SELECT a.idpenjualan, a.idjenissampah, b.nama AS jenissampah, b.harga AS harga, b.satuan AS satuan, a.jumlah
                        FROM barang_penjualan AS a
                        LEFT JOIN jenis_sampah AS b ON a.idjenissampah = b.id
                        WHERE a.idpenjualan = %s',
                        $idpenjualan,
                    );
        return DB::select(DB::raw($sql));
    }


}
