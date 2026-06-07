<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main"> <!-- Wrapper utama agar layout tidak pecah [3] -->
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Kategori</li>
            <li class="active">Master Data Kategori</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Master Data Kategori
                        <!-- Tombol tambah data mengarah ke input kategori [3] -->
                        <a href="<?php echo base_url('admin/input-data-kategori');?>">
                            <button type="button" class="btn btn-sm btn-primary pull-right">Input Data Kategori</button>
                        </a>
                    </h3>
                    <hr />
                    <!-- Penggunaan plugin Bootstrap Table untuk fitur search dan pagination [4] -->
                    <table data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th data-sortable="true">#</th>
                                <th data-sortable="true">ID Kategori</th>
                                <th data-sortable="true">Nama Kategori</th>
                                <th data-sortable="true">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            // Melakukan perulangan data dari variabel $data_kat yang dikirim controller [4, 5]
                            foreach($data_kat as $data){
                            ?>
                            <tr>
                                <td><?php echo ++$no;?></td>
                                <td><?php echo $data['id_kategori'];?></td>
                                <td><?php echo $data['nama_kategori'];?></td>
                                <td>
                                    <!-- Tombol Edit dengan ID yang disamarkan menggunakan sha1 [6, 7] -->
                                    <a href="<?= base_url('admin/edit-data-kategori/').sha1($data['id_kategori']);?>">
                                        <button type="button" class="btn btn-sm btn-success">Edit</button>
                                    </a>
                                    <!-- Tombol Hapus memicu fungsi JavaScript SweetAlert [4] -->
                                    <a href="#" onclick="doDelete('<?= sha1($data['id_kategori']);?>')">
                                        <button type="button" class="btn btn-sm btn-danger">Hapus</button>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!--/.main-->

<script type="text/javascript">
    // Fungsi konfirmasi hapus menggunakan SweetAlert2 [4, 8]
    function doDelete(idDelete){
        swal({
            title : "Hapus Data Kategori?",
            text : "Data ini akan terhapus secara permanen!!",
            icon : "warning",
            buttons : true,
            dangerMode : false,
        })
        .then(ok => {
            if(ok){
                // Mengarahkan ke rute hapus jika user menekan OK [4]
                window.location.href = '<?= base_url();?>/admin/hapus-data-kategori/' + idDelete;
            }
        })
    }
</script>