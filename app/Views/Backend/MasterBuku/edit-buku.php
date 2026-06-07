<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= base_url('admin/master-data-buku'); ?>">Master Data Buku</a></li>
            <li class="active">Edit Data Buku</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Edit Data Buku
                        <a href="<?= base_url('admin/master-data-buku'); ?>">
                            <button type="button" class="btn btn-sm btn-default pull-right">Kembali</button>
                        </a>
                    </h3>
                    <hr />

                    <!-- Flashdata error -->
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/update-buku'); ?>" method="post" enctype="multipart/form-data">

                        <!-- Judul Buku -->
                        <div class="form-group">
                            <label>Judul Buku</label>
                            <input type="text" name="judul_buku" class="form-control"
                                value="<?= $dataBuku['judul_buku']; ?>" required>
                        </div>

                        <!-- Pengarang -->
                        <div class="form-group">
                            <label>Pengarang</label>
                            <input type="text" name="pengarang" class="form-control"
                                value="<?= $dataBuku['pengarang']; ?>" required>
                        </div>

                        <!-- Penerbit -->
                        <div class="form-group">
                            <label>Penerbit</label>
                            <input type="text" name="penerbit" class="form-control"
                                value="<?= $dataBuku['penerbit']; ?>" required>
                        </div>

                        <!-- Tahun -->
                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" name="tahun" class="form-control"
                                value="<?= $dataBuku['tahun']; ?>" required>
                        </div>

                        <!-- Jumlah Eksemplar -->
                        <div class="form-group">
                            <label>Jumlah Eksemplar</label>
                            <input type="number" name="jumlah_eksemplar" class="form-control"
                                value="<?= $dataBuku['jumlah_eksemplar']; ?>" required>
                        </div>

                        <!-- Kategori Buku -->
                        <div class="form-group">
                            <label>Kategori Buku</label>
                            <select name="kategori_buku" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach($data_kategori as $kat): ?>
                                    <option value="<?= $kat['id_kategori']; ?>"
                                        <?= ($kat['id_kategori'] == $dataBuku['id_kategori']) ? 'selected' : ''; ?>>
                                        <?= $kat['nama_kategori']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"><?= $dataBuku['keterangan']; ?></textarea>
                        </div>

                        <!-- Rak -->
                        <div class="form-group">
                            <label>Rak</label>
                            <select name="rak" class="form-control" required>
                                <option value="">-- Pilih Rak --</option>
                                <?php foreach($data_rak as $rak): ?>
                                    <option value="<?= $rak['id_rak']; ?>"
                                        <?= ($rak['id_rak'] == $dataBuku['id_rak']) ? 'selected' : ''; ?>>
                                        <?= $rak['nama_rak']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Cover Buku -->
                        <div class="form-group">
                            <label>Cover Buku</label><br>
                            <!-- Preview cover saat ini -->
                            <img src="<?= base_url('Assets/CoverBuku/').$dataBuku['cover_buku']; ?>"
                                width="100px" class="img-thumbnail" style="margin-bottom:10px"><br>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti cover</small>
                            <input type="file" name="cover_buku" class="form-control" accept=".jpg,.jpeg,.png">
                        </div>

                        <!-- E-Book -->
                        <div class="form-group">
                            <label>E-Book (PDF)</label><br>
                            <small class="text-muted">File saat ini: <strong><?= $dataBuku['e_book']; ?></strong></small><br>
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti e-book</small>
                            <input type="file" name="e_book" class="form-control" accept=".pdf">
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('admin/master-data-buku'); ?>">
                            <button type="button" class="btn btn-default">Batal</button>
                        </a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>