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

                            <h4 class="card-title mb-1 text-center">OTP sent to your email</h4>

                            <?php echo form_open('auth/confirm_otp', ['autocomplete' => 'off', 'id' => 'form-login']); ?>
                                <div class="form-group">
                                    <label for="email" class="form-label">OTP <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="otp" name="otp" placeholder="" value="" aria-describedby="email" tabindex="1" autofocus>
									<b class="text-danger"><?= form_error('otp'); ?></b>
								</div>
                                <button type="submit" id="submit" class="btn btn-primary btn-block" tabindex="4">Submit</button>
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
