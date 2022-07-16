<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id','idalamat', 'iduser', 'totalpembelian', 'tanggal', 'idkurir', 'status', 'statusprogress'
    ];

    static function createPembelian($idalamat, $iduser, $totalpembelian, $idkurir){
        $info = new Pembelian;
 
        $info->idalamat = $idalamat;
        $info->iduser = $iduser;
        $info->totalpembelian = $totalpembelian;
        $info->tanggal = new DateTime();
        $info->status = '1';
        $info->idkurir = $idkurir;
        $info->save();
        
        return $info->id;
    }

    static function allDataProduk(){
        $sql = sprintf(
                        'SELECT a.id, a.idjenis, a.nama, a.foto, a.harga, a.satuan, c.nama as mitra, d.nama as jenis_barang 
                        FROM produk as a
                        LEFT JOIN user as b ON b.id = a.iduser
                        LEFT JOIN info_mitra as c ON b.id = c.iduser
                        LEFT JOIN jenis_produk as d ON d.id = a.idjenis
                        WHERE a.status <> -1'
                    );
        return DB::select(DB::raw($sql));
    }

    static function allJenisProduk(){
        $sql = sprintf(
                        'SELECT a.id, a.nama, a.iddaerah, b.nama as daerah
                        FROM jenis_produk AS a
                        LEFT JOIN daerah AS b ON a.iddaerah = b.id
                        WHERE a.status <> -1'
                    );
        return DB::select(DB::raw($sql));
    }

    static function getDataProduk($idjenisproduk){
        $sql = sprintf(
                        'SELECT a.id, a.idjenis, a.nama, a.foto, a.harga, a.satuan, c.nama as mitra, d.nama as jenis_barang 
                        FROM produk as a
                        LEFT JOIN user as b ON b.id = a.iduser
                        LEFT JOIN info_mitra as c ON b.id = c.iduser
                        LEFT JOIN jenis_produk as d ON d.id = a.idjenis
                        WHERE a.idjenis = %s AND a.status <> -1',
                        $idjenisproduk
                    );
        return DB::select(DB::raw($sql));
    }

    static function getJenisProduk($idjenisproduk){
        $sql = sprintf(
                        'SELECT a.id, a.nama, a.iddaerah, b.nama as daerah
                        FROM jenis_produk AS a
                        LEFT JOIN daerah AS b ON a.iddaerah = b.id
                        WHERE a.id = %s AND a.status <> -1',
                        $idjenisproduk
                    );
        return DB::select(DB::raw($sql));
    }

    static function getDetailProduk($idproduk){
        $sql = sprintf(
                        'SELECT a.id, a.nama as namaproduk, b.nama as jenis, a.foto, a.deskripsi, a.harga, a.satuan, d.nama as mitra, e.nama as daerah, d.foto as fotomitra
                        FROM produk AS a
                        LEFT JOIN jenis_produk AS b ON a.idjenis = b.id
                        LEFT JOIN user as c ON c.id = a.iduser
                        LEFT JOIN info_mitra as d ON c.id = d.iduser
                        LEFT JOIN daerah as e ON e.id = d.iddaerah
                        WHERE a.id = %s  AND a.status <> -1
                        LIMIT 1',
                        $idproduk,
                    );
        return DB::select(DB::raw($sql));
    }

     static function getHistoryPembelian($iduser){
        $sql = sprintf(
                        'SELECT a.iduser, a.id, d.nama, b.nama AS alamat, c.nama AS daerah, a.tanggal, 
                            (SELECT COUNT(*)
                            FROM barang_pembelian
                            WHERE idpembelian = a.id) AS jumlah_jenis, IF(a.statusprogress = 0, "Menunggu response", IF(a.statusprogress = 1, "Menuju toko", IF(a.statusprogress = 2, "Dalam perjalanan", "Selesai"))) as progress_kurir, "Pembelian Produk" as title, 2 as jenistransaksi
                        FROM pembelian AS a
                        LEFT JOIN user_alamat AS b ON a.idalamat = b.id
                        LEFT JOIN daerah AS c ON b.iddaerah = c.id
                        LEFT JOIN user AS d ON d.id = a.iduser
                        WHERE a.idkurir = %s ORDER BY a.tanggal DESC',
                        $iduser,
                    );
        return DB::select(DB::raw($sql));
    }

    static function getDetailPembelian($idpembelian){
        $sql = sprintf(
                        'SELECT a.id, b.nama AS alamat, b.detail AS detailalamat, d.nama as mitra, a.totalpembelian, a.tanggal, c.nama as namaresiker, c.nohp, IF(a.statusprogress = 0, "Menunggu response", IF(a.statusprogress = 1, "Menuju toko", IF(a.statusprogress = 2, "Dalam perjalanan", "Selesai"))) as progress_kurir, a.statusprogress as status, b.gpsl, b.gpsb, a.idkurir, e.nama as namakurir, e.deviceid as hwidkurir, d.gpsl as gpslmitra, d.gpsb as gpsbmitra
                        FROM pembelian AS a
                        LEFT JOIN user_alamat AS b ON a.idalamat = b.id
                        LEFT JOIN user AS c ON a.iduser = c.id
                        LEFT JOIN info_mitra AS d ON d.iduser = c.id
                        LEFT JOIN user AS e ON a.idkurir = e.id
                        WHERE a.id = %s  AND a.status <> -1
                        LIMIT 1',
                        $idpembelian,
                    );
        return DB::select(DB::raw($sql));
    }


    static function getDetailBarang($idpembelian){
        $sql = sprintf(
                        'SELECT a.idpembelian, a.idproduk, b.nama AS namaproduk, c.nama AS jenisproduk, b.harga AS harga, b.satuan AS satuan, a.jumlah, b.foto
                        FROM barang_pembelian AS a
                        LEFT JOIN produk AS b ON a.idproduk = b.id
                        LEFT JOIN jenis_produk AS c ON b.idjenis = c.id
                        WHERE a.idpembelian = %s',
                        $idpembelian,
                    );
        return DB::select(DB::raw($sql));
    }



    static function updateProgress($id, $foto){
        $up = Pembelian::find($id);
        $stts = "1";
        if($up->statusprogress == 1){
            $stts = "2";
        }else if($up->statusprogress == 2){
            $stts = "3";
        }

        $up->statusprogress = $stts;
        $up->save();
        return $stts;
    }

    static function getTotalPembelianResiker($iduser){
        $sql = sprintf(
                        'SELECT iduser, sum(totalpembelian) as total from pembelian where iduser = %s and statusprogress = 3',
                        $iduser,
                    );
        $total = DB::select(DB::raw($sql));
        return sizeof($total) > 0 ? ($total[0]->total ? $total[0]->total  : 0) : 0;
    }

     static function getLaporanPembelian($iddaerah){
        $filter = "";
        if(!empty($iddaerah)){
            $filter = "WHERE c.id = ".$iddaerah;
        }

        $sql = sprintf(
                        'SELECT a.iduser, a.id, d.nama, b.nama AS alamat, c.nama AS daerah, a.tanggal, 
                            (SELECT COUNT(*)
                            FROM barang_pembelian
                            WHERE idpembelian = a.id) AS jumlah_jenis, IF(a.statusprogress = 0, "Menunggu response", IF(a.statusprogress = 1, "Menuju toko", IF(a.statusprogress = 2, "Dalam perjalanan", "Selesai"))) as progress_kurir, "Pembelian Produk" as title, 2 as jenistransaksi
                        FROM pembelian AS a
                        LEFT JOIN user_alamat AS b ON a.idalamat = b.id
                        LEFT JOIN daerah AS c ON b.iddaerah = c.id
                        LEFT JOIN user AS d ON d.id = a.iduser
                        %s
                        ORDER BY a.tanggal DESC',
                        $filter
                    );
        return DB::select(DB::raw($sql));
    }
}
