<?php
namespace App\Models;

use CodeIgniter\Model;

class M_Buku extends Model
{
    protected $table = 'tbl_buku';

    public function getDataBuku($where = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        if ($where !== false) {
            $builder->where($where);
        }
        $builder->orderBy('judul_buku', 'ASC');
        return $builder->get();
    }

    public function getDataBukuJoin($where = false)
    {
        $builder = $this->db->table($this->table);
        
        // FIX: tambah id_kategori dan id_rak agar form edit bisa selected
        $builder->select('
            tbl_buku.id_buku,
            tbl_buku.id_kategori,
            tbl_buku.id_rak,
            tbl_buku.judul_buku,
            tbl_buku.pengarang,
            tbl_buku.penerbit,
            tbl_buku.tahun,
            tbl_buku.jumlah_eksemplar,
            tbl_buku.keterangan,
            tbl_buku.cover_buku,
            tbl_buku.e_book,
            tbl_buku.is_delete_buku,
            tbl_kategori.nama_kategori,
            tbl_rak.nama_rak
        ');
        
        $builder->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_buku.id_kategori', 'LEFT');
        $builder->join('tbl_rak', 'tbl_rak.id_rak = tbl_buku.id_rak', 'LEFT');
        if ($where !== false) {
            $builder->where($where);
        }
        $builder->orderBy('tbl_buku.judul_buku', 'ASC');
        return $builder->get();
    }

    public function saveDataBuku($data)
    {
        $builder = $this->db->table($this->table);
        return $builder->insert($data);
    }

    public function updateDataBuku($data, $where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data);
    }

    // Soft delete
    public function hapusDataBuku($where)
    {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update([
            'is_delete_buku' => '1',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function autoNumber()
    {
        $builder = $this->db->table($this->table);
        $builder->select("id_buku");
        $builder->orderBy("id_buku", "DESC");
        $builder->limit(1);
        return $builder->get();
    }
}