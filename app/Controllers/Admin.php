<?php

namespace App\Controllers;

// Load models sesuai Bab V [3]
use App\Models\M_Admin;
use App\Models\M_Anggota;
use App\Models\M_Kategori;
use App\Models\M_Rak;
use App\Models\M_Buku;

class Admin extends BaseController
{
    // --- FUNGSI LOGIN & AUTENTIKASI ---

    // Menampilkan halaman login [4]
    public function login()
    {
        return view('Backend/Login/login');
        
    }

    // Fungsi untuk proses login [3, 5, 6]
    public function autentikasi()
    {
        $modelAdmin = new M_Admin; // proses inisiasi model [3]
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cek apakah username ada dan tidak sedang dihapus [3]
        $cekUsername = $modelAdmin->getDataAdmin(['username_admin' => $username, 'is_delete_admin' => '0'])->getNumRows();
        
        if ($cekUsername == 0) {
            session()->setFlashdata('error', 'Username Tidak Ditemukan!');
            ?>
            <script>
                history.go(-1);
            </script>
            <?php
        } else {
            $dataUser = $modelAdmin->getDataAdmin(['username_admin' => $username, 'is_delete_admin' => '0'])->getRowArray();
            $passwordUser = $dataUser['password_admin'];

            // Verifikasi password hash [3]
            $verifikasiPassword = password_verify($password, $passwordUser);
            
            if (!$verifikasiPassword) {
                session()->setFlashdata('error', 'Password Tidak Sesuai!');
                ?>
                <script>
                    history.go(-1);
                </script>
                <?php
            } else {
                // Set data ke dalam Session [6]
                $dataSession = [
                    'ses_id' => $dataUser['id_admin'],
                    'ses_user' => $dataUser['nama_admin'],
                    'ses_level' => $dataUser['akses_level']
                ];
                session()->set($dataSession);
                session()->setFlashdata('success', 'Login Berhasil!');
                ?>
                <script>
                    document.location = "<?= base_url('admin/dashboard-admin'); ?>";
                </script>
                <?php
            }
        }
    }

    // Menampilkan Dashboard dengan proteksi Session [6]
    public function dashboard()
    {
        if (session()->get('ses_id') == "" or session()->get('ses_user') == "" or session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?>
            <script>
                document.location = "<?= base_url('admin/login-admin'); ?>";
            </script>
            <?php
        } else {
            echo view('Backend/Template/header');
            echo view('Backend/Template/sidebar');
            echo view('Backend/Login/dashboard_admin');
            echo view('Backend/Template/footer');
        }
    }

    // Fungsi Logout [7]
    public function logout()
    {
        session()->remove('ses_id');
        session()->remove('ses_user');
        session()->remove('ses_level');
        session()->setFlashdata('info', 'Anda telah keluar dari sistem!');
        ?>
        <script>
            document.location = "<?= base_url('admin/login-admin'); ?>";
        </script>
        <?php
    }

    // --- MODUL CRUD ADMIN ---

    public function master_data_admin()
    {
        if (session()->get('ses_id') == "" or session()->get('ses_user') == "" or session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?><script>document.location = "<?= base_url('admin/login-admin'); ?>";</script><?php
        } else {
            $modelAdmin = new M_Admin;
            $uri = service('uri');
            $pages = $uri->getSegment(2);
            $dataUser = $modelAdmin->getDataAdmin(['is_delete_admin' => '0', 'akses_level !=' => '1'])->getResultArray();

            $data['pages'] = $pages;
            $data['data_user'] = $dataUser;

            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/MasterAdmin/master-data-admin', $data);
            echo view('Backend/Template/footer', $data);
        }
    }

    public function input_data_admin()
    {
        if (session()->get('ses_id') == "" or session()->get('ses_user') == "" or session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?><script>document.location = "<?= base_url('admin/login-admin'); ?>";</script><?php
        } else {
            echo view('Backend/Template/header');
            echo view('Backend/Template/sidebar');
            echo view('Backend/MasterAdmin/input-admin');
            echo view('Backend/Template/footer');
        }
    }

    public function simpan_data_admin()
    {
        if (session()->get('ses_id') == "" or session()->get('ses_user') == "" or session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            ?><script>document.location = "<?= base_url('admin/login-admin'); ?>";
        </script>
        <?php
        } else {
            $modelAdmin = new M_Admin;
            $nama = $this->request->getPost('nama');
            $username = $this->request->getPost('username');
            $level = $this->request->getPost('level');

            $cekUname = $modelAdmin->getDataAdmin(['username_admin' => $username])->getNumRows();
            if ($cekUname > 0) {
                session()->setFlashdata('error', 'Username sudah digunakan!!');
                ?><script>history.go(-1);</script><?php
            } else {
                $hasil = $modelAdmin->autoNumber()->getRowArray();
                if (!$hasil) {
                    $id = "ADM001";
                } else {
                    $kode = $hasil['id_admin'];
                    $noUrut = (int) substr($kode, -3);
                    $noUrut++;
                    $id = "ADM" . sprintf("%03s", $noUrut);
                }

                $dataSimpan = [
                    'id_admin' => $id,
                    'nama_admin' => $nama,
                    'username_admin' => $username,
                    'password_admin' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'akses_level' => $level,
                    'is_delete_admin' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $modelAdmin->saveDataAdmin($dataSimpan);
                session()->setFlashdata('success', 'Data Admin Berhasil Ditambahkan!!');
                ?>
                <script>
                    document.location = "<?= base_url('admin/master-data-admin'); ?>";
                </script>
                <?php
            }
        }
    }

    public function edit_data_admin()
    {
        $uri = service('uri');
        $idEdit = $uri->getSegment(3);
        $modelAdmin = new M_Admin;
        
        $dataAdmin = $modelAdmin->getDataAdmin(['sha1(id_admin)' => $idEdit])->getRowArray();
        session()->set(['idUpdate' => $dataAdmin['id_admin']]);

        $data['page'] = $uri->getSegment(2);
        $data['web_title'] = "Edit Data Admin";
        $data['data_admin'] = $dataAdmin;

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAdmin/edit-admin', $data);
        echo view('Backend/Template/footer', $data);
    }

   
    public function update_admin()
    {
        $modelAdmin = new M_Admin;
        $idUpdate = session()->get('idUpdate');
        $nama = $this->request->getPost('nama');
        $level = $this->request->getPost('level');

        if ($nama == "" or $level == "") {
            session()->setFlashdata('error', 'Isian tidak boleh kosong!!');
            ?><script>history.go(-1);</script><?php
        } else {
            $dataUpdate = [
                'nama_admin' => $nama,
                'akses_level' => $level,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $whereUpdate = ['id_admin' => $idUpdate];

            $modelAdmin->updateDataAdmin($dataUpdate, $whereUpdate);
            session()->remove('idUpdate');
            session()->setFlashdata('success', 'Data Admin Berhasil Diperbaharui!');
            ?><script>document.location = "<?= base_url('admin/master-data-admin'); ?>";</script><?php
        }
    }

   
    public function hapus_data_admin()
    {
        $modelAdmin = new M_Admin;
        $uri = service('uri');
        $idHapus = $uri->getSegment(3);

        $dataUpdate = [
            'is_delete_admin' => '1',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $whereUpdate = ['sha1(id_admin)' => $idHapus];

        $modelAdmin->updateDataAdmin($dataUpdate, $whereUpdate);
        session()->setFlashdata('success', 'Data Admin Berhasil Dihapus!');
        ?><script>document.location = "<?= base_url('admin/master-data-admin'); ?>";</script><?php
    }




    // --- MODUL CRUD ANGGOTA ---

    public function master_data_anggota()
    {
        // FIX: Samakan pola cek session seperti modul lain
        if (session()->get('ses_id') == "" or session()->get('ses_user') == "" or session()->get('ses_level') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to('admin/login-admin');
        }

        $modelAnggota = new M_Anggota;
        // Mengambil data anggota dengan status is_delete_anggota = '0'
        $dataAnggota = $modelAnggota->getDataAnggota(['is_delete_anggota' => '0'])->getResultArray();

        $data['data_agt'] = $dataAnggota;
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAnggota/master-data-anggota', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_anggota()
    {
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterAnggota/input-anggota');
        echo view('Backend/Template/footer');
    }

    public function simpan_data_anggota()
    {
        $modelAnggota = new M_Anggota;

        $hasil = $modelAnggota->autoNumber()->getRowArray();

        if (!$hasil) {
            $id = "AGT001";
        } else {
            $kode = $hasil['id_anggota'];
            $noUrut = (int) substr($kode, -3);
            $noUrut++;
            $id = "AGT" . sprintf("%03d", $noUrut);
        }

        $dataSimpan = [
            'id_anggota' => $id,
            'nama_anggota' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jk'),
            'no_telp' => $this->request->getPost('telp'),
            'alamat' => $this->request->getPost('alamat'),
            'email' => $this->request->getPost('email'),
            'password_anggota' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_delete_anggota' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $modelAnggota->saveDataAnggota($dataSimpan);

        return redirect()->to(base_url('admin/master-data-anggota'));
    }

    public function edit_data_anggota()
    {
        $idEdit = service('uri')->getSegment(3);
        $modelAnggota = new M_Anggota;
        $dataAnggota = $modelAnggota->getDataAnggota(['sha1(id_anggota)' => $idEdit])->getRowArray();
        
        // Simpan ID asli ke session untuk proses update nanti
        session()->set(['idUpdateAgt' => $dataAnggota['id_anggota']]);

        $data['data_agt'] = $dataAnggota;
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAnggota/edit-anggota', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_anggota()
    {
        $modelAnggota = new M_Anggota;
        $idUpdate = session()->get('idUpdateAgt');
        $dataUpdate = [
            'nama_anggota'  => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jk'),
            'no_telp'       => $this->request->getPost('telp'),
            'alamat'        => $this->request->getPost('alamat'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $modelAnggota->updateDataAnggota($dataUpdate, ['id_anggota' => $idUpdate]);
        session()->remove('idUpdateAgt');
        session()->setFlashdata('success', 'Data Anggota Berhasil Diperbaharui!');
        return redirect()->to('admin/master-data-anggota');
    }

    public function hapus_data_anggota()
    {
        $modelAnggota = new M_Anggota;
        $idHapus = service('uri')->getSegment(3);
        
        $dataUpdate = ['is_delete_anggota' => '1'];
        $modelAnggota->updateDataAnggota($dataUpdate, ['sha1(id_anggota)' => $idHapus]);
        
        session()->setFlashdata('success', 'Data Anggota Berhasil Dihapus!');
        return redirect()->to('admin/master-data-anggota');
    }




    // --- MODUL CRUD KATEGORI ---

    // Tampil Data Kategori
    public function master_data_kategori()
    {
        $modelKat = new M_Kategori;
        $data['data_kat'] = $modelKat->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterKategori/master-data-kategori', $data);
        echo view('Backend/Template/footer', $data);
    }

    // Form Input Kategori
    public function input_data_kategori()
    {
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterKategori/input-kategori');
        echo view('Backend/Template/footer');
    }

    // Simpan Data Kategori
    public function simpan_data_kategori()
    {
        $modelKat = new M_Kategori;
        $hasil = $modelKat->autoNumber()->getRowArray();
        $id = (!$hasil) ? "KTG001" : "KTG" . sprintf("%03s", (int)substr($hasil['id_kategori'], -3) + 1);

        $dataSimpan = [
            'id_kategori' => $id,
            'nama_kategori' => $this->request->getPost('nama'),
            'is_delete_kategori' => '0',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $modelKat->saveDataKategori($dataSimpan);
        session()->setFlashdata('success', 'Kategori Berhasil Ditambahkan!!');
        return redirect()->to('admin/master-data-kategori');
    }

    // Form Edit Kategori
    public function edit_data_kategori()
    {
        $idEdit = service('uri')->getSegment(3);
        $modelKat = new M_Kategori;
        $dataKat = $modelKat->getDataKategori(['sha1(id_kategori)' => $idEdit])->getRowArray();
        session()->set(['idUpdateKat' => $dataKat['id_kategori']]);

        $data['data_kat'] = $dataKat;
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterKategori/edit-kategori', $data);
        echo view('Backend/Template/footer', $data);
    }

    // Update Data Kategori
    public function update_data_kategori()
    {
        $modelKat = new M_Kategori;
        $idUpdate = session()->get('idUpdateKat');
        $modelKat->updateDataKategori(['nama_kategori' => $this->request->getPost('nama')], ['id_kategori' => $idUpdate]);
        session()->remove('idUpdateKat');
        session()->setFlashdata('success', 'Kategori Berhasil Diperbaharui!');
        return redirect()->to('admin/master-data-kategori');
    }

    // Hapus Data Kategori (Soft Delete)
    public function hapus_data_kategori()
    {
        $modelKat = new M_Kategori;
        $idHapus = service('uri')->getSegment(3);
        $modelKat->updateDataKategori(['is_delete_kategori' => '1'], ['sha1(id_kategori)' => $idHapus]);
        session()->setFlashdata('success', 'Kategori Berhasil Dihapus!');
        return redirect()->to('admin/master-data-kategori');
    }


    // --- MODUL CRUD RAK ---

    // Tampil Data Rak
    public function master_data_rak()
    {
        $modelRak = new M_Rak;
        $data['data_rak'] = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterRak/master-data-rak', $data);
        echo view('Backend/Template/footer', $data);
    }

    // Form Input Rak
    public function input_data_rak()
    {
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterRak/input-rak');
        echo view('Backend/Template/footer');
    }

    // Simpan Data Rak
    public function simpan_data_rak()
    {
        $modelRak = new M_Rak;
        $hasil = $modelRak->autoNumber()->getRowArray();
        $id = (!$hasil) ? "RAK001" : "RAK" . sprintf("%03s", (int)substr($hasil['id_rak'], -3) + 1);
        
        $dataSimpan = [
            'id_rak' => $id,
            'nama_rak' => $this->request->getPost('nama'),
            'is_delete_rak' => '0',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $modelRak->saveDataRak($dataSimpan);
        session()->setFlashdata('success', 'Data Rak Berhasil Ditambahkan!!');
        return redirect()->to('admin/master-data-rak');
    }

    // Form Edit Rak
    public function edit_data_rak()
    {
        $idEdit = service('uri')->getSegment(3);
        $modelRak = new M_Rak;
        $dataRak = $modelRak->getDataRak(['sha1(id_rak)' => $idEdit])->getRowArray();
        session()->set(['idUpdateRak' => $dataRak['id_rak']]);
        
        $data['data_rak'] = $dataRak;
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterRak/edit-rak', $data);
        echo view('Backend/Template/footer', $data);
    }

    // Update Data Rak
    public function update_data_rak()
    {
        $modelRak = new M_Rak;
        $idUpdate = session()->get('idUpdateRak');
        $modelRak->updateDataRak(['nama_rak' => $this->request->getPost('nama')], ['id_rak' => $idUpdate]);
        session()->remove('idUpdateRak');
        session()->setFlashdata('success', 'Data Rak Berhasil Diperbaharui!');
        return redirect()->to('admin/master-data-rak');
    }

    // Hapus Data Rak (Soft Delete)
    public function hapus_data_rak()
    {
        $modelRak = new M_Rak;
        $idHapus = service('uri')->getSegment(3);
        $modelRak->updateDataRak(['is_delete_rak' => '1'], ['sha1(id_rak)' => $idHapus]);
        session()->setFlashdata('success', 'Data Rak Berhasil Dihapus!');
        return redirect()->to('admin/master-data-rak');
    }


    // --- MODUL CRUD BUKU ---

    public function master_buku()
    {
        $modelBuku = new M_Buku;
        
        // Mengambil data keseluruhan buku dari table buku di database yang belum dihapus
        $dataBuku = $modelBuku->getDataBukuJoin(['tbl_buku.is_delete_buku' => '0'])->getResultArray();

        $uri = service('uri');
        $page = $uri->getSegment(2);

        $data['page'] = $page;
        $data['web_title'] = "Master Data Buku";
        $data['dataBuku'] = $dataBuku; // mengirim array data buku ke view

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/master-data-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_buku()
    {
        $modelKategori = new M_Kategori;
        $modelRak = new M_Rak;
        $uri = service('uri');
        $page = $uri->getSegment(2);

        $data['page'] = $page;
        $data['web_title'] = "Input Data Buku";
        // Mengambil data kategori dan rak yang belum dihapus
        $data['data_kategori'] = $modelKategori->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
        $data['data_rak'] = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/input-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function simpan_buku()
    {
        $modelBuku = new M_Buku;

        // Ambil inputan dari form
        $judulBuku       = $this->request->getPost('judul_buku');
        $pengarang       = $this->request->getPost('pengarang');
        $penerbit        = $this->request->getPost('penerbit');
        $tahun           = $this->request->getPost('tahun');
        $jumlahEksemplar = $this->request->getPost('jumlah_eksemplar');
        $kategoriBuku    = $this->request->getPost('kategori_buku');
        $keterangan      = $this->request->getPost('keterangan');
        $rak             = $this->request->getPost('rak');

        // FIX: Validasi field teks tidak boleh kosong
        if (empty($judulBuku) || empty($pengarang) || empty($penerbit) || empty($tahun)) {
            session()->setFlashdata('error', 'Field tidak boleh kosong!');
            return redirect()->to('/admin/input-buku')->withInput();
        }

        // --- PROSES VALIDASI & UPLOAD FILE ---
        // Validasi Cover Buku (Max 1MB, format gambar)
        if (!$this->validate([
            'cover_buku' => 'uploaded[cover_buku]|max_size[cover_buku, 1024]|ext_in[cover_buku,jpg,jpeg,png]',
        ])) {
            session()->setFlashdata('error', "Format file yang diizinkan : jpg, jpeg, png dengan maksimal ukuran 1 MB");
            return redirect()->to('/admin/input-buku')->withInput();
        }

        // Validasi E-Book (Max 10MB, format PDF)
        if (!$this->validate([
            'e_book' => 'uploaded[e_book]|max_size[e_book, 10240]|ext_in[e_book,pdf]',
        ])) {
            session()->setFlashdata('error', "Format file yang diizinkan : pdf dengan maksimal ukuran 10 MB");
            return redirect()->to('/admin/input-buku')->withInput();
        }

        // Proses pindah file dan Rename
        $coverBuku  = $this->request->getFile('cover_buku');
        $ext1       = $coverBuku->getClientExtension();
        $namaFile1  = "Cover-Buku-" . date("ymdHis") . "." . $ext1;
        $coverBuku->move('Assets/CoverBuku', $namaFile1); // Pindah ke folder CoverBuku [2]

        $eBook      = $this->request->getFile('e_book');
        $ext2       = $eBook->getClientExtension();
        $namaFile2  = "E-Book-" . date("ymdHis") . "." . $ext2;
        $eBook->move('Assets/E-Book', $namaFile2); // Pindah ke folder E-Book [2]

        // --- LOGIKA PENOMORAN OTOMATIS (BKU001, dst) ---
        $hasil = $modelBuku->autoNumber()->getRowArray();
        if (!$hasil) {
            $id = "BKU001";
        } else {
            $kode   = $hasil['id_buku'];
            // Ambil angka saja dari belakang, hapus huruf BKU
            $noUrut = (int) substr($kode, 3); // ambil dari karakter ke-4 dst (setelah BKU)
            $noUrut++;
            $id = "BKU" . sprintf("%03d", $noUrut);
        }

        // --- PROSES SIMPAN KE DATABASE ---
        $dataSimpan = [
            'id_buku'          => $id,
            'judul_buku'       => ucwords($judulBuku),
            'pengarang'        => ucwords($pengarang),
            'penerbit'         => ucwords($penerbit),
            'tahun'            => $tahun,
            'jumlah_eksemplar' => $jumlahEksemplar,
            'id_kategori'      => $kategoriBuku,
            'keterangan'       => $keterangan,
            'id_rak'           => $rak,
            'cover_buku'       => $namaFile1,
            'e_book'           => $namaFile2,
            'is_delete_buku'   => '0',
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s')
        ];

        $modelBuku->saveDataBuku($dataSimpan);
        session()->setFlashdata('success', 'Data Buku Berhasil Ditambahkan!!');
        return redirect()->to('admin/master-data-buku');
    }

    public function hapus_buku()
    {
        $modelBuku = new M_Buku;
        $uri       = service('uri');
        $idHapus   = $uri->getSegment(3);

        // Cari data buku berdasarkan sha1 id
        $dataHapus = $modelBuku->getDataBuku(['sha1(id_buku)' => $idHapus])->getRowArray();

        if ($dataHapus) {
            // Hapus file fisik cover
            $coverPath = 'Assets/CoverBuku/' . $dataHapus['cover_buku'];
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }

            // Hapus file fisik e-book
            $ebookPath = 'Assets/E-Book/' . $dataHapus['e_book'];
            if (file_exists($ebookPath)) {
                unlink($ebookPath);
            }

            // Soft delete di database
            $modelBuku->hapusDataBuku(['sha1(id_buku)' => $idHapus]);
        }

        // FIX: Ganti echo script JS ke redirect()
        session()->setFlashdata('success', 'Data Buku Berhasil Dihapus!');
        return redirect()->to('admin/master-data-buku');
    }
    // Form Edit Buku
// --- TAMBAHKAN INI DI Admin.php ---

public function edit_buku()
{
    $uri     = service('uri');
    $idEdit  = $uri->getSegment(3);

    $modelBuku     = new M_Buku;
    $modelKategori = new M_Kategori;
    $modelRak      = new M_Rak;

    // FIX: pakai getDataBukuJoin agar id_kategori dan id_rak ikut terbawa
    $dataBuku = $modelBuku->getDataBukuJoin(['sha1(tbl_buku.id_buku)' => $idEdit])->getRowArray();

    if (!$dataBuku) {
        session()->setFlashdata('error', 'Data buku tidak ditemukan!');
        return redirect()->to('admin/master-data-buku');
    }

    // Simpan id asli ke session untuk proses update
    session()->set(['idUpdateBuku' => $dataBuku['id_buku']]);

    $data['dataBuku']      = $dataBuku;
    $data['data_kategori'] = $modelKategori->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
    $data['data_rak']      = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();
    $data['web_title']     = "Edit Data Buku";

    echo view('Backend/Template/header', $data);
    echo view('Backend/Template/sidebar', $data);
    echo view('Backend/MasterBuku/edit-buku', $data);
    echo view('Backend/Template/footer', $data);
}


// Proses Update Buku
public function update_buku()
{
    $modelBuku = new M_Buku;
    $idUpdate  = session()->get('idUpdateBuku');

    $judulBuku       = $this->request->getPost('judul_buku');
    $pengarang       = $this->request->getPost('pengarang');
    $penerbit        = $this->request->getPost('penerbit');
    $tahun           = $this->request->getPost('tahun');
    $jumlahEksemplar = $this->request->getPost('jumlah_eksemplar');
    $kategoriBuku    = $this->request->getPost('kategori_buku');
    $keterangan      = $this->request->getPost('keterangan');
    $rak             = $this->request->getPost('rak');

    $dataUpdate = [
        'judul_buku'       => ucwords($judulBuku),
        'pengarang'        => ucwords($pengarang),
        'penerbit'         => ucwords($penerbit),
        'tahun'            => $tahun,
        'jumlah_eksemplar' => $jumlahEksemplar,
        'id_kategori'      => $kategoriBuku,
        'keterangan'       => $keterangan,
        'id_rak'           => $rak,
        'updated_at'       => date('Y-m-d H:i:s')
    ];

    // Cek apakah ada upload cover buku baru
    $coverBuku = $this->request->getFile('cover_buku');
    if ($coverBuku && $coverBuku->isValid() && !$coverBuku->hasMoved()) {
        if (!$this->validate(['cover_buku' => 'max_size[cover_buku,1024]|ext_in[cover_buku,jpg,jpeg,png]'])) {
            session()->setFlashdata('error', 'Format cover: jpg/jpeg/png, maksimal 1 MB');
            return redirect()->to('admin/edit-buku/' . sha1($idUpdate))->withInput();
        }
        $ext1 = $coverBuku->getClientExtension();
        $namaFile1 = "Cover-Buku-" . date("ymdHis") . "." . $ext1;
        $coverBuku->move('Assets/CoverBuku', $namaFile1);
        $dataUpdate['cover_buku'] = $namaFile1;
    }

    // Cek apakah ada upload e-book baru
    $eBook = $this->request->getFile('e_book');
    if ($eBook && $eBook->isValid() && !$eBook->hasMoved()) {
        if (!$this->validate(['e_book' => 'max_size[e_book,10240]|ext_in[e_book,pdf]'])) {
            session()->setFlashdata('error', 'Format e-book: pdf, maksimal 10 MB');
            return redirect()->to('admin/edit-buku/' . sha1($idUpdate))->withInput();
        }
        $ext2 = $eBook->getClientExtension();
        $namaFile2 = "E-Book-" . date("ymdHis") . "." . $ext2;
        $eBook->move('Assets/E-Book', $namaFile2);
        $dataUpdate['e_book'] = $namaFile2;
    }

    $modelBuku->updateDataBuku($dataUpdate, ['id_buku' => $idUpdate]);
    session()->remove('idUpdateBuku');
    session()->setFlashdata('success', 'Data Buku Berhasil Diperbaharui!');
    return redirect()->to('admin/master-data-buku');
}
}