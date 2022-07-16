<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class PenjualanSampah extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id','idalamat', 'iduser', 'foto', 'totalpenjualan', 'idjadwal', 'tanggal', 'status', 'statusprogress'
    ];

    static function getJadwalAmbil($iddaerah){
        $sql = sprintf(
                        'SELECT *
                        from jadwal_ambil_sampah
                        where 
                        iddaerah = %s',
                        $iddaerah,
                    );
        return DB::select(DB::raw($sql));
    }

    static function getJenisSampah($iddaerah){
        $sql = sprintf(
                        'SELECT *
                        from jenis_sampah
                        where 
                        iddaerah = %s and status <> -1',
                        $iddaerah,
                    );
        return DB::select(DB::raw($sql));
    }


    static function createPenjualan($idalamat, $iduser, $idjadwal, $foto, $totalpenjualan, $idkurir){
        $info = new PenjualanSampah;
 
        $info->idalamat = $idalamat;
        $info->iduser = $iduser;
        $info->idjadwal = $idjadwal;
        $info->foto = $foto;
        $info->totalpenjualan = $totalpenjualan;
        $info->tanggal = new DateTime();
        $info->status = '1';
        $info->idkurir = $idkurir;
        $info->save();
        
        return $info->id;
    }

    static function getHistoryPenjualan($iduser){
        $sql = sprintf(
                        'SELECT a.iduser, a.id, b.nama AS alamat, c.nama AS daerah, a.tanggal, 
                        (SELECT COUNT(*)
                        FROM barang_penjualan
                        WHERE idpenjualan = a.id) AS jumlah_jenis
                        FROM penjualan AS a
                        LEFT JOIN user_alamat AS b ON a.idalamat = b.id
                        LEFT JOIN daerah AS c ON b.iddaerah = c.id
                        WHERE a.iduser = %s ORDER BY a.tanggal DESC',
                        $iduser,
                    );
        return DB::select(DB::raw($sql));
    }


    static function getHistoryOrder($iduser){
        $sql = sprintf(
                        '(SELECT a.id, a.tanggal, b.nama as nama, c.jam as jam, d.detail as alamat, e.nama as daerah, IF(a.statusprogress = 0, "Menunggu Penjemputan", IF(a.statusprogress = 1, "Dalam Perjalanan", "Selesai")) as progress_kurir, "Penjualan Sampah" as title, 1 as jenistransaksi, a.statusprogress
                        FROM penjualan as a
                        LEFT JOIN user as b ON a.iduser = b.id
                        LEFT JOIN jadwal_ambil_sampah as c ON a.idjadwal = c.id
                        LEFT JOIN user_alamat as d ON a.idalamat = d.id
                        LEFT JOIN daerah as e ON d.iddaerah = e.id
                        WHERE a.iduser = %s  and a.status <> -1
                        )
                        UNION ALL
                        (
                        SELECT a.id, a.tanggal, b.nama as nama, "0" as jam, d.detail as alamat, e.nama as daerah, IF(a.statusprogress = 0, "Menunggu response", IF(a.statusprogress = 1, "Menuju toko", IF(a.statusprogress = 2, "Dalam perjalanan", "Selesai"))) as progress_kurir, "Pembelian Produk" as title, 2 as jenistransaksi, a.statusprogress
                        FROM pembelian as a
                        LEFT JOIN user as b ON a.iduser = b.id
                        LEFT JOIN user_alamat as d ON a.idalamat = d.id
                        LEFT JOIN daerah as e ON d.iddaerah = e.id
                        WHERE a.iduser = %s  and a.status <> -1
                        ) order by statusprogress asc, tanggal desc, jenistransaksi asc',
                        $iduser,
                        $iduser,
                    );
        return DB::select(DB::raw($sql));
    }

    static function getDetailPenjualan($idpenjualan){
        $sql = sprintf(
                        'SELECT a.id, b.nama AS alamat, b.detail AS detailalamat, a.iduser, a.foto, a.totalpenjualan, c.jam AS jadwal, a.tanggal, d.nama as resiker, d.nohp, IF(a.statusprogress = 0, "Menunggu Penjemputan", IF(a.statusprogress = 1, "Menuju Resiker" , "Selesai")) as progress_kurir, a.statusprogress as status, a.statusprogress as status, b.gpsl, b.gpsb, a.idkurir, e.nama as namakurir, e.deviceid as hwidkurir
                        FROM penjualan AS a
                        LEFT JOIN user_alamat AS b ON a.idalamat = b.id
                        LEFT JOIN jadwal_ambil_sampah AS c ON a.idjadwal = c.id
                        LEFT JOIN user AS d on a.iduser = d.id
                        LEFT JOIN user AS e ON a.idkurir = e.id
                        WHERE a.id = %s  AND a.status <> -1
                        LIMIT 1',
                        $idpenjualan,
                    );
        return DB::select(DB::raw($sql));
    }


    static function getDetailBarang($idpenjualan){
        $sql = sprintf(
                        'SELECT a.id, a.idpenjualan, a.idjenissampah, b.nama AS jenissampah, b.harga AS harga, b.satuan AS satuan, a.jumlah
                        FROM barang_penjualan AS a
                        LEFT JOIN jenis_sampah AS b ON a.idjenissampah = b.id
                        WHERE a.idpenjualan = %s',
                        $idpenjualan,
                    );
        return DB::select(DB::raw($sql));
    }

    static function getTotalPenjualanResiker($iduser){
        $sql = sprintf(
                        'SELECT iduser, sum(totalpenjualan) as total from penjualan where iduser = %s and statusprogress = 2',
                        $iduser,
                    );
        $total = DB::select(DB::raw($sql));
        return sizeof($total) > 0 ? ($total[0]->total ? $total[0]->total  : 0) : 0;
    }

    static function getTransactionHistory($iduser){
        $sql = sprintf(
                    '   (
                            SELECT a.id, a.tanggal, a.totalpenjualan as total, "Penjualan Sampah" as title, 1 as jenistransaksi, a.statusprogress
                            FROM penjualan as a
                            LEFT JOIN user as b ON a.iduser = b.id
                            LEFT JOIN jadwal_ambil_sampah as c ON a.idjadwal = c.id
                            LEFT JOIN user_alamat as d ON a.idalamat = d.id
                            LEFT JOIN daerah as e ON d.iddaerah = e.id
                            WHERE a.iduser = %s  and a.status <> -1 and statusprogress = 2
                        )
                        UNION ALL
                        (
                            SELECT a.id, a.tanggal, a.totalpembelian as total, "Pembelian Produk" as title, 2 as jenistransaksi, a.statusprogress
                            FROM pembelian as a
                            LEFT JOIN user as b ON a.iduser = b.id
                            LEFT JOIN user_alamat as d ON a.idalamat = d.id
                            LEFT JOIN daerah as e ON d.iddaerah = e.id
                            WHERE a.iduser = %s  and a.status <> -1 and statusprogress = 3
                        )
                        UNION ALL
                        (
                            SELECT a.id, a.tanggal, a.total, "Tabungan" as title, 3 as jenistransaksi, 1
                            FROM tabunganuser as a
                            LEFT JOIN user as b ON a.iduser = b.id
                            WHERE a.iduser = %s 
                        )
                         UNION ALL
                        (
                            SELECT a.id, a.tanggal, a.total, "Sedekah" as title, 4 as jenistransaksi, 1
                            FROM sedekahuser as a
                            LEFT JOIN user as b ON a.iduser = b.id
                            WHERE a.iduser = %s 
                        ) order by tanggal desc',
                        $iduser,
                        $iduser,
                        $iduser,
                        $iduser
                );
        return DB::select(DB::raw($sql));
    }

    static function getLastTransaction($iddaerah){
        $sql = sprintf(
                    '   (
                            SELECT a.id, a.tanggal, a.totalpenjualan as total, "Penjualan Sampah" as title, 1 as jenistransaksi, a.statusprogress, b.nama as namauser
                            FROM penjualan as a
                            LEFT JOIN user as b ON a.iduser = b.id
                            LEFT JOIN jadwal_ambil_sampah as c ON a.idjadwal = c.id
                            LEFT JOIN user_alamat as d ON a.idalamat = d.id
                            LEFT JOIN daerah as e ON d.iddaerah = e.id
                            WHERE e.id = %s  and a.status <> -1 and statusprogress = 2
                        )
                        UNION ALL
                        (
                            SELECT a.id, a.tanggal, a.totalpembelian as total, "Pembelian Produk" as title, 2 as jenistransaksi, a.statusprogress, b.nama as namauser
                            FROM pembelian as a
                            LEFT JOIN user as b ON a.iduser = b.id
                            LEFT JOIN user_alamat as d ON a.idalamat = d.id
                            LEFT JOIN daerah as e ON d.iddaerah = e.id
                            WHERE e.id = %s  and a.status <> -1 and statusprogress = 3
                        )
                         UNION ALL
                        (
                            SELECT a.id, a.tanggal, a.total, "Sedekah" as title, 4 as jenistransaksi, 1, b.nama as namauser
                            FROM sedekahuser as a
                            LEFT JOIN user as b ON a.iduser = b.id
                            LEFT JOIN sedekah as c on a.idsedekah = c.id
                            WHERE c.id = %s
                        ) order by tanggal desc limit 10',
                        $iddaerah,
                        $iddaerah,
                        $iddaerah
                );
        return DB::select(DB::raw($sql));
    }


    static function getLaporanPenjualanAdmin($iddaerah){
        $filter = "";
        if(!empty($iddaerah)){
            $filter = "WHERE e.id = ".$iddaerah;
        }
        $sql = sprintf(
                'SELECT a.id, a.tanggal, b.nama as nama, c.jam as jam, d.detail as alamat, e.id as iddaerah, e.nama as daerah, IF(a.statusprogress = 0, "Menunggu Penjemputan", IF(a.statusprogress = 1, "Dalam perjalanan" , "Selesai")) as progress_kurir
                FROM penjualan as a
                LEFT JOIN user as b ON a.iduser = b.id
                LEFT JOIN jadwal_ambil_sampah as c ON a.idjadwal = c.id
                LEFT JOIN user_alamat as d ON a.idalamat = d.id
                LEFT JOIN daerah as e ON d.iddaerah = e.id
                        %s
                ORDER BY a.tanggal DESC',
                $filter
            );
        return DB::select(DB::raw($sql));
    }
}
