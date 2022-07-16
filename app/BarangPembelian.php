<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class BarangPembelian extends Model
{
    protected $table = 'barang_pembelian';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'idpembelian', 'idproduk', 'jumlah'
    ];


    static function createBarangPembelian($idpembelian, $idproduk, $jumlah){
        $info = new BarangPembelian;
 
        $info->idpembelian = $idpembelian;
        $info->idproduk = $idproduk;
        $info->jumlah = $jumlah;
        $info->save();
        
        return $info->id;
    }


    static function getLaporanMitra($iduser){
        $sql = sprintf(
                        'SELECT
                            bp.id as idbarangpembelian, bp.jumlah, p.nama, mitra.nama, im.nama as namamitra, pm.tanggal, customer.nama as namacustomer, p.harga, (bp.jumlah * p.harga) as pendapatan, p.satuan
                            from barang_pembelian bp
                            left join produk p on bp.idproduk = p.id
                            left join user mitra on mitra.id = p.iduser
                            left join info_mitra im on mitra.id = im.iduser
                            left join pembelian pm on bp.idpembelian = pm.id
                            left join user customer on customer.id = pm.iduser
                            where mitra.id = %s',
                        $iduser,
                    );
        return DB::select(DB::raw($sql));
    }

}
