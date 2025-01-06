<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">User</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('user'); ?>">User</a>
                            </li>
                            <li class="breadcrumb-item active">Edit
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <?php echo form_open_multipart($form['action'], ['autocomplete' => 'off', 'method' => 'POST', 'id' => 'form-edit-user', 'class' => '']); ?>
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Edit User Information</h4>
                        </div>
                        <div class="card-body">
							<div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['first_name']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['last_name']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['email']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Mobile <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['contact']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Role</label>
										<?php echo form_dropdown($form['group']['name'], $form['group']['options'], $form['group']['selected'], $form['group']['attr']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Status</label>
										<?php echo form_dropdown($form['status']['name'], $form['status']['options'], $form['status']['selected'], $form['status']['attr']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Old Password</label>
                                        <?php echo form_input($form['old_password']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">New Password <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['new_password']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="submit" form="form-edit-user" class="btn btn-primary data-submit mr-1">Save Changes</button>
                            <a href="<?php echo site_url('user'); ?>" class="btn btn-outline-secondary waves-effect" data-id="2">Back</a>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    // remove profile pic
    var removeProfilePic = $('.remove-profile');
    removeProfilePic.on('click', function () {
        let userId = $(this).attr('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ml-1'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    beforeSend  : function(request) {
                        request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
                    },
                    url: <?php echo json_encode(site_url("api/profile/deletepic")); ?>,
                    type: "POST",
                    data: {
                        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
                        user_id: userId
                    }
                }).done(function(data) {
                    if (data == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'User profile picture has been removed.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        }).then(function (result) {
                            // redirect to profile page
                            window.location.href = "<?php echo site_url('user'); ?><?php echo $user->id; ?>/form";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        });
                    }
                });
            }
        });
    });


    // submit form
    var form = $("#form-edit-user");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });
});
</script>
