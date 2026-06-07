<?php
namespace App\Models;

use CodeIgniter\Model;

class M_Anggota extends Model{
    protected $table = 'tbl_anggota'; // Sesuai Bab III halaman 39 [4]

    public function getDataAnggota($where = false) {
        if ($where === false) {
            $builder = $this->db->table($this->table);
            $builder->select('*');
            $builder->orderBy('nama_anggota', 'ASC'); // Menggunakan orderBy [1]
            return $query = $builder->get();
        } else {  
            $builder = $this->db->table($this->table);
            $builder->select('*');
            $builder->where($where);
            $builder->orderBy('nama_anggota', 'ASC');
            return $query = $builder->get();
        }
    }

    public function saveDataAnggota($data) {
        $builder = $this->db->table($this->table);
        return $builder->insert($data); // Sesuai pola simpan data di Bab V [1]
    }

    public function updateDataAnggota($data, $where) {
        $builder = $this->db->table($this->table);
        $builder->where($where);
        return $builder->update($data); // Sesuai pola update data di Bab V [2]
    }

    public function autoNumber() {
        $builder = $this->db->table($this->table);
        $builder->select("id_anggota");
        $builder->orderBy("id_anggota", "DESC"); // Wajib DESC untuk mendapatkan ID terakhir [2, 5]
        $builder->limit(1);
        return $query = $builder->get();
    }
}
