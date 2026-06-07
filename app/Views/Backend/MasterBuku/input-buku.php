<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main"> <!-- Wrapper utama -->
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Buku</li>
            <li class="active">Input Data Buku</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Input Buku</h3>
                    <hr />
                    <!-- Penting: Pastikan ada enctype="multipart/form-data" untuk proses upload -->
                    <form action="<?= base_url('admin/simpan-buku');?>" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group col-md-12">
                            <label>Judul Buku</label>
                            <input type="text" class="form-control" name="judul_buku" placeholder="Masukkan Judul Buku" required="required">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Pengarang</label>
                            <input type="text" class="form-control" name="pengarang" placeholder="Masukkan Nama Pengarang" required="required">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Penerbit</label>
                            <input type="text" class="form-control" name="penerbit" placeholder="Masukkan Nama Penerbit" required="required">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Tahun</label>
                            <input type="number" class="form-control" name="tahun" placeholder="Masukkan Tahun Terbit" required="required">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Jumlah Eksemplar</label>
                            <input type="number" class="form-control" name="jumlah_eksemplar" placeholder="Masukkan Jumlah Eksemplar" required="required">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Kategori Buku</label>
                            <select class="form-control" name="kategori_buku" required="required">
                                <option value="">-- Pilih Kategori Buku --</option>
                                <?php foreach($data_kategori as $kat) : ?>
                                    <option value="<?= $kat['id_kategori'];?>"><?= $kat['nama_kategori'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Rak</label>
                            <select class="form-control" name="rak" required="required">
                                <option value="">-- Pilih Rak --</option>
                                <?php foreach($data_rak as $rak) : ?>
                                    <option value="<?= $rak['id_rak'];?>"><?= $rak['nama_rak'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3" placeholder="Masukkan Keterangan Buku"></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Cover Buku</label>
                            <input type="file" class="form-control" name="cover_buku" required="required">
                            <small class="text-danger">Format file yang diizinkan: jpg, jpeg, png. Maksimal 1 MB [1, 2].</small>
                        </div>

                        <div class="form-group col-md-6">
                            <label>E-Book</label>
                            <input type="file" class="form-control" name="e_book" required="required">
                            <small class="text-danger">Format file yang diizinkan: pdf. Maksimal 10 MB [1, 2].</small>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="<?= base_url('admin/master-buku');?>" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>