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

                            <h4 class="card-title mb-1 text-center">Welcome to ASA! &#128075;</h4>

                            <?php echo form_open('login', ['autocomplete' => 'off', 'id' => 'form-login']); ?>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>" aria-describedby="email" tabindex="1" autofocus>
									<b class="text-danger"><?= form_error('email'); ?></b>
									<?php if (!empty($invalid_email)): ?>
										<b class="text-danger"><?= $invalid_email ?></b>
									<?php endif; ?>
								</div>

                                <div class="form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="login-password">Password <span class="text-danger">*</span></label>
                                        <a href="<?php echo site_url('forgot'); ?>">
                                            <small>Forgot Password?</small>
                                        </a>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password">
                                        <div class="input-group-append">
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
									<b class="text-danger"><?= form_error('password'); ?></b>
									<?php if (!empty($invalid_password)): ?>
										<b class="text-danger"><?= $invalid_password ?></b>
									<?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="remember-me" tabindex="3" />
                                        <label class="custom-control-label" for="remember-me"> Remember Me </label>
                                    </div>
                                </div>
                                <button type="submit" id="submit" class="btn btn-primary btn-block" tabindex="4">Login</button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {

    // submit form
    var form = $("#form-login");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

});
</script>
