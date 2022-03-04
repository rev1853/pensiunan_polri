<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <p class="h1"><b>Login</b></p>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('user_error_message')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('user_error_message'); ?>
                    <?= $this->session->unmark_flash('user_error_message'); ?>
                </div>
            <?php endif; ?>
            <form action="<?= base_url() . "login/cek-login-user"; ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" style="display: none">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <input type="text" class="form-control <?= form_error('username') ? 'is-invalid' : ''; ?>" placeholder="username" name="username" value="<?= set_value('username'); ?>">
                    <?= form_error('username', '<div class="invalid-feedback">', '</div>'); ?>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <input type="password" class="form-control <?= form_error('password') ? 'is-invalid' : ''; ?>" placeholder="Password" name="password">
                    <?= form_error('password', '<div class="invalid-feedback">', '</div>'); ?>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4"></div>
                    <div class="col-4">
                        <button type="reset" class="btn btn-danger btn-block">Reset</button>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
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