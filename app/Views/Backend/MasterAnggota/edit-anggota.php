<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Anggota</li>
            <li class="active">Edit Data Anggota</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Edit Anggota</h3>
                    <hr />
                    <form action="<?= base_url('admin/update-data-anggota');?>" method="post">
                        <div class="form-group col-md-6">
                            <label>Nama Anggota</label>
                            <input type="text" class="form-control" name="nama" value="<?= $data_agt['nama_anggota'];?>" placeholder="Masukkan Nama Anggota" required="required">
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <label>Jenis Kelamin</label>
                            <select class="form-control" name="jk" required="required">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" <?php if($data_agt['jenis_kelamin']=="L"){ echo "selected"; }?>>Laki-laki</option>
                                <option value="P" <?php if($data_agt['jenis_kelamin']=="P"){ echo "selected"; }?>>Perempuan</option>
                            </select>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <label>No. Telepon</label>
                            <input type="number" class="form-control" name="telp" value="<?= $data_agt['no_tlp'];?>" placeholder="Masukkan No. Telepon" required="required">
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <label>Email (Username)</label>
                            <input type="email" class="form-control" name="email" value="<?= $data_agt['email'];?>" readonly="readonly" placeholder="Masukkan Email" required="required">
                            <small class="text-danger">*Email tidak dapat diubah karena digunakan sebagai identitas login.</small>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-12">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap" required="required"><?= $data_agt['alamat'];?></textarea>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?= base_url('admin/master-data-anggota');?>"><button type="button" class="btn btn-danger">Batal</button></a>
                        </div>
                        <div style="clear:both;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>