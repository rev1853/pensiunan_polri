<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Pensiun</h1>
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
                                    <input class="form-control date-filter" autocomplete="off" placeholder="Pilih Tanggal Pensiun" type="text" value="" id="example-text-input">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-12 mb-2">
                            <div class="row">
                                <div class="col-12">
                                    <a href="<?= base_url(); ?>data-pensiun/data-excel" class="btn btn-success">Ekspor Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="mytable table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nrp</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">Pangkat</th>
                                <th scope="col">Kesatuan</th>
                                <th scope="col">Gaji</th>
                                <th scope="col">tgl pensiun</th>
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
                "url": "<?= site_url('data-pensiun/data') ?>",
                "type": "POST",
                "data": function(d) {
                    d.mydata = data;
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
            'templateResult': formatRepo,
            'templateSelection': formatRepoSelection
        });

        // DATE RANGE PICKER
        let daterange = $('.date-filter').daterangepicker({
            'autoUpdateInput': false,
            'locale': {
                'cancelLabel': 'Clear'
            }
        });

        $('.date-filter').on('apply.daterangepicker', function(ev, picker) {
            data.tglpensiun = {
                'start': picker.startDate.format("YYYY-MM-DD"),
                'end': picker.endDate.format("YYYY-MM-DD")
            };

            $(this).val(picker.startDate.format("YYYY/MM/DD") + ' - ' + picker.endDate.format("YYYY/MM/DD"));

            datatable.draw();
        });

        $('.date-filter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');

            delete data.tglpensiun;
            datatable.draw();
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

    function formatRepoSelection(repo) {
        return repo.text;
    }
</script>