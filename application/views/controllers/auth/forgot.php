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
                            
                            <h4 class="card-title mb-1 text-center">Forgot Password? &#128273;</h4>
                            <p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>
                            
                            <?php echo form_open('forgot', ['autocomplete' => 'off', 'class' => 'auth-login-form mt-2']); ?>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="johndoe@gmail.com" aria-describedby="email" tabindex="1" autofocus required />
                                </div>

                                <button type="submit" class="btn btn-primary btn-block" tabindex="4">Send Reset Link</button>
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