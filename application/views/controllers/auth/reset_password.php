<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
    <div class="content-header row"></div>
        <div class="content-body login-page-background">
            <div class="auth-wrapper auth-v1 px-2">
                <div class="auth-inner py-2">
                    <div class="card mb-0">
                        <div class="card-body">
                            <p class="card-text mb-2 text-center">
								<img class="img-fluid" width="120px" src="<?php echo assets_url('img/logo.png'); ?>" alt="Logo" />
                            </p>

                            <h4 class="card-title mb-1 text-center">Reset Password &#128273;</h4>
                            <?php echo form_open($form['submit'], ['autocomplete' => 'off', 'class' => 'auth-login-form mt-2']); ?>
                                <div class="form-group">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" value="" id="password" class="form-control" autofocus required />
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" value="" id="confirm_password" class="form-control" required />
                                </div>

                                <button type="submit" class="btn btn-primary btn-block" tabindex="4">Reset</button>
                            <?php echo form_close(); ?>

                            <p class="text-center mt-2">
                              <a href="<?php echo site_url('login'); ?>"> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg> Back to login </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
