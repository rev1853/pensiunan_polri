<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Data User</h1>
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

                <div class="card-body alert-container">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="username" placeholder="Masukkan Username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="email" placeholder="Masukkan Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="telepon" placeholder="Masukkan No. Telepon">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" placeholder="Masukkan Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="confirmpassword" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="confirmpassword" placeholder="Konfirmasi Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="role">
                                        <option value="polres">Polres</option>
                                        <option value="polda">Polda</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Kesatuan" class="col-sm-2 col-form-label">Kesatuan</label>
                                <div class="col-sm-10">
                                    <select class="form-control myselect" id="kesatuan"></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="button" id="btn-tambah" class="btn btn-success">Tambah</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(e => {
        $("#btn-tambah").on('click', () => {
            let data = new FormData();
            data.append("nama", $('#nama').val());
            data.append("email", $('#email').val());
            data.append("telepon", $('#telepon').val());
            data.append("username", $('#username').val());
            data.append("password", $('#password').val());
            data.append("confirmpassword", $('#confirmpassword').val());
            data.append("role", $('#role').val());
            data.append("kesatuan", $('#kesatuan').val());

            $.ajax({
                "url": "<?= base_url(); ?>user/proses-tambah",
                "data": data,
                "type": "post",
                "processData": false,
                "contentType": false,
                "success": (res) => {

                    let response = JSON.parse(res);
                    $('.invalid-feedback').remove();
                    $('input').removeClass('is-invalid');
                    $('select').removeClass('is-invalid');

                    if (response.error) {
                        let error = response.error;
                        $.each(error, (i, e) => {

                            $('#' + i).addClass('is-invalid');
                            $('#' + i).parent().append($(`
                                <div class="invalid-feedback">
                                    ${e}
                                </div>
                            `));
                        });
                    } else {
                        let alert;
                        if (response.success) {
                            alert = $(`
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i>Berhasil</h5>
                                Data berhasil ditambahkan
                            </div>
                            `);
                        } else {
                            alert = $(`
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i>Gagal</h5>
                                Data gagal ditambahkan
                            </div>
                            `);
                        }
                        $('.alert-container').prepend(alert);
                        $('input').val('');
                        $('option[value="polres"]').attr('selected', true);
                    }
                }
            });
        });

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


    });





    function formatRepo2(repo) {
        if (repo.loading) {
            return repo.text;
        }

        var $container = $(
            "<div class='select2-result-repository clearfix2'>" +
            "<div class='select2-result-repository__title2'></div>" +
            "</div>"
        );

        $container.find(".select2-result-repository__title2").text(repo.text);

        return $container;
    }

    function formatRepoSelection2(repo) {
        return repo.text;
    }
</script>