<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Rak</li>
            <li class="active">Master Data Rak</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Master Data Rak
                        <a href="<?= base_url('admin/input-data-rak');?>"><button class="btn btn-sm btn-primary pull-right">Input Data Rak</button></a>
                    </h3>
                    <hr />
                    <table data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Rak</th>
                                <th>Nama Rak</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0; foreach($data_rak as $data){ ?>
                            <tr>
                                <td><?= ++$no; ?></td>
                                <td><?= $data['id_rak']; ?></td>
                                <td><?= $data['nama_rak']; ?></td>
                                <td>
                                    <a href="<?= base_url('admin/edit-data-rak/').sha1($data['id_rak']);?>"><button class="btn btn-sm btn-success">Edit</button></a>
                                    <a href="#" onclick="doDelete('<?= sha1($data['id_rak']);?>')"><button class="btn btn-sm btn-danger">Hapus</button></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function doDelete(id) {
    swal({ title: "Hapus Rak?", text: "Data ini akan terhapus secara permanen!!", icon: "warning", buttons: true })
    .then(ok => { if(ok) window.location.href = '<?= base_url('admin/hapus-data-rak/'); ?>/' + id; });
}
</script>