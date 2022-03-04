<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Home</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                            <div class="row">
                                <div class="col-12">
                                    <select class="form-control myselect" id="exampleSelect1"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                            <div class="row">
                                <div class="col-12">
                                    <select class="form-control myselect2" id="exampleSelect2"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= base_url(); ?>user/tambah" class="btn btn-primary">Tambah Data</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('delete_success_message')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= $this->session->flashdata('delete_success_message'); ?>
                            <?= $this->session->unmark_flash('delete_success_message'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('delete_error_message')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $this->session->flashdata('delete_error_message'); ?>
                            <?= $this->session->unmark_flash('delete_error_message'); ?>
                        </div>
                    <?php endif; ?>
                    <table class="mytable table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Username</th>
                                <th scope="col">Telepon</th>
                                <th scope="col">Role</th>
                                <th scope="col">Kesatuan</th>
                                <th scope="col">Pangkat</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    let data = {};
    $(document).ready(() => {

        let datatable = $('.mytable').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "language": {
                "emptyTable": "Tidak ada data tersedia di tabel",
                "zeroRecords": "Data tidak ditemukan",
                "infoEmpty": "Menampilkan 0 dari 0 data",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ total data",
                "infoFiltered": "(dipilih dari _MAX_ data)",
                "search": "Cari:",
                "processing": "Diproses...",
            },
            "order": [],
            "pageLength": 50,
            "ajax": {
                "url": "<?= site_url('user/data') ?>",
                "type": "POST",
                "data": function(d) {
                    d.mydata = data;
                },
                "beforeSend": (e) => {
                    console.log(e);
                }
            },
            "columnDefs": [{
                "targets": [0],
                "orderable": false
            }],
        });

        // SELECT2 JS
        $('.myselect').select2({
            'placeholder': "Pilih Polres",
            'allowClear': true,
            'theme': 'bootstrap4',
            'width': '100%',
            'ajax': {
                'url': '<?= base_url(); ?>data-pensiun/get-kesatuan',
                'data': function(params) {
                    return {
                        'search': params.term,
                        'page': params.page || 0
                    }
                },
                'type': 'POST',
                'delay': 250,
                'dataType': 'json',
                'processResults': function(data, params) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    params.page = params.page || 0;
                    return {
                        'results': data.results,
                        'pagination': {
                            'more': data.count
                        }
                    };
                },
                'cache': true
            },
            'templateResult': formatRepo2,
            'templateSelection': formatRepoSelection2
        });

        // SELECT2 JS PANGKAT
        $('.myselect2').select2({
            'placeholder': "Pilih Pangkat",
            'allowClear': true,
            'theme': 'bootstrap4',
            'width': '100%',
            'ajax': {
                'url': '<?= base_url(); ?>data-pensiun/get-pangkat',
                'data': function(params) {
                    return {
                        'search': params.term,
                        'page': params.page || 0
                    }
                },
                'type': 'POST',
                'delay': 200,
                'dataType': 'json',
                'processResults': function(data, params) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    params.page = params.page || 0;
                    return {
                        'results': data.results,
                        'pagination': {
                            'more': data.count
                        }
                    };
                },
                'cache': true
            },
            'templateResult': formatRepo,
            'templateSelection': formatRepoSelection
        });

        $('.search').on('keyup', function() {
            data.search = $(this).val();
            if (data.search == '') {
                delete data.search;
            }
            datatable.draw();
        });

        $('.myselect').on('select2:select', function(e) {
            data.kesatuan = e.params.data.text;
            datatable.draw();
        });

        $('.myselect').on('select2:clear', function(e) {
            delete data.kesatuan;
            datatable.draw();
        });

        $('.myselect2').on('select2:select', function(e) {
            data.pangkat = e.params.data.text;
            datatable.draw();
        });

        $('.myselect2').on('select2:clear', function(e) {
            delete data.pangkat;
            datatable.draw();
        });
    });

    function formatRepo(repo) {
        if (repo.loading) {
            return repo.text;
        }

        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__title'></div>" +
            "</div>"
        );

        $container.find(".select2-result-repository__title").text(repo.text);

        return $container;
    }

    function formatRepoSelection2(repo) {
        return repo.text;
    }

    function formatRepo2(repo) {
        if (repo.loading) {
            return repo.text;
        }

        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__title'></div>" +
            "</div>"
        );

        $container.find(".select2-result-repository__title").text(repo.text);

        return $container;
    }

    function formatRepoSelection(repo) {
        return repo.text;
    }
</script>