<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Admin</li>
            <li class="active">Master Data Admin</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Master Data Admin</h3>
                    <!-- Tombol untuk menuju form Create -->
                    <a href="<?= base_url('admin/input-data-admin');?>">
                        <button type="button" class="btn btn-sm btn-primary pull-right">Input Data Admin</button>
                    </a>
                    <hr />
                    
                    <table data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                        <thead>
                            <tr>
                                <th data-sortable="true">#</th>
                                <th data-sortable="true">Nama Admin</th>
                                <th data-sortable="true">Username Admin</th>
                                <th data-sortable="true">Akses Level</th>
                                <th data-sortable="true">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            // Implementasi Perulangan FOREACH untuk menampilkan data [1]
                            foreach($data_user as $data){
                            ?>
                            <tr>
                                <td data-sortable="true"><?php echo $no=$no+1;?></td>
                                <td data-sortable="true"><?= $data['nama_admin'];?></td>
                                <td data-sortable="true"><?= $data['username_admin'];?></td>
                                <td data-sortable="true"><?= $data['akses_level'];?></td>
                                <td data-sortable="true">
                                    <?php
                                    // Implementasi Percabangan IF...ELSE untuk hak akses opsi [1]
                                    if(session()->get('ses_level')=="1"){
                                    ?>
                                        <a href="<?= base_url('admin/edit-data-admin/').sha1($data['id_admin']);?>">
                                            <button type="button" class="btn btn-sm btn-success">Edit</button>
                                        </a>
                                        <a href="#" onclick="doDelete('<?= sha1($data['id_admin']);?>')">
                                            <button type="button" class="btn btn-sm btn-danger">Hapus</button>
                                        </a>
                                    <?php
                                    }
                                    else echo "#"; 
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!--/.row-->
</div><!--/.main-->

<!-- Script Konfirmasi Hapus dengan Sweetalert2 -->
<script type="text/javascript">
    function doDelete(idDelete){
        swal({
            title : "Hapus Data Admin?",
            text : "Data ini akan terhapus secara permanen!!",
            icon : "warning",
            buttons : true,
            dangerMode : false,
        })
        .then(ok => {
            if(ok){
                // Mengalihkan ke fungsi Delete di Controller [1]
                window.location.href = '<?= base_url();?>/admin/hapus-data-admin/' + idDelete;
            }
            else{
                $(this).removeAttr('disabled')
            }
        })
    }
</script>
