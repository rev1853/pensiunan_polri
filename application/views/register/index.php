<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <p class="h1"><b>Register</b></p>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('register_error_message')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('register_error_message'); ?>
                    <?= $this->session->unmark_flash('register_error_message'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('register_success_message')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= $this->session->flashdata('register_success_message'); ?>
                    <?= $this->session->unmark_flash('register_success_message'); ?>
                </div>
            <?php endif; ?>
            <form action="<?= base_url() . "register/$action_url"; ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" style="display: none">
                <input type="hidden" class="form-control <?= form_error('nrp') ? 'is-invalid' : ''; ?>" placeholder="NRP" name="nrp" value="<?= set_value('nrp'); ?>">
                <div class="form-group">
                    <label class="col-form-label" for="NRP">NRP</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <input type="text" id="NRP" class="form-control <?= form_error('nrp') ? 'is-invalid' : ''; ?>" placeholder="NRP" name="<?= $nrp_exist ? 'dummynrp' : 'nrp'; ?>" value="<?= set_value('nrp'); ?>">
                        <?= form_error('nrp', '<div class="invalid-feedback">', '</div>'); ?>
                    </div>
                </div>
                <?php if ($nrp_exist) : ?>
                    <div class="form-group">
                        <label class="col-form-label">Nama</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <input type="username" class="form-control" placeholder="Nama" value="<?= isset($nama) ? $nama : set_value('nama'); ?>" name="nama">
                            <?= form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                    </div>
                    <div class="w-100 mb-3 border-bottom border-1"></div>
                    <div class="form-group">
                        <label class="col-form-label" for="password">Password</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <input type="password" id="password" autofocus="true" class="form-control <?= form_error('password') ? 'is-invalid' : ''; ?>" placeholder="Masukkan Password" name="password">
                            <?= form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label" for="confirm_password">Konfirmasi Password</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <input type="password" id="confirm_password" autofocus="true" class="form-control <?= form_error('confirm_password') ? 'is-invalid' : ''; ?>" placeholder="Konfirmasi Password" name="confirm_password">
                            <?= form_error('confirm_password', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4"></div>
                    <div class="col-4">
                        <button type="reset" class="btn btn-danger btn-block">Reset</button>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <!-- /.social-auth-links -->
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->