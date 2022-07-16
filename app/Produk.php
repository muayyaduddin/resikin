<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
    	'id', 'idjenis', 'iduser' ,'nama', 'foto', 'deskripsi', 'harga', 'satuan'
    ];

    static function isNamaExist($iduser, $nama){
        $prod = new Produk;
        return $prod->where([ ["iduser", $iduser], ["nama", $nama] ])->first() ? true : false;
    }

    static function getListProduk($iddaerah, $keyword, $iduser = ""){
        $filtermitra = "";
        if(!empty($iduser)){
            $filtermitra = " and a.iduser = ".$iduser;
        }


        $sql = sprintf(
                        'SELECT a.* , b.nama as kategori
                        from produk as a
                        left join jenis_produk as b on a.idjenis = b.id
                        where 
                        b.iddaerah = %s and (a.nama like "%%%s%%" or a.deskripsi like "%%%s%%") and b.status <> -1 %s',
                        $iddaerah,
                        $keyword,
                        $keyword,
                        $filtermitra
                    );
        return DB::select(DB::raw($sql));
    }

    static function getDetailProduk($id){
        $prod = new Produk;
        return $prod->where([ ["id", $id] ])->first();
    }

    static function createProduk($idjenis, $iduser, $nama, $foto, $deskripsi, $harga, $satuan){
        $produk = new Produk;
 
        $produk->idjenis = $idjenis;
        $produk->iduser = $iduser;
        $produk->nama = $nama;
        $produk->foto = $foto;
        $produk->deskripsi = $deskripsi;
        $produk->harga = $harga;
        $produk->satuan = $satuan;
        $produk->save();
        
        return $produk->id;
    }

    // static function updateJenisProduk($id, $nama){
    //     $jp = JenisProduk::find($id);
    //     $jp->nama = $nama;
    //     $jp->save();
    // }

    static function hapusProduk($id){
        $prod = Produk::find($id);
        $prod->status = -1;
        $prod->save();
    }
}
