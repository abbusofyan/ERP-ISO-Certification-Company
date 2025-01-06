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
                            <li class="breadcrumb-item">
								Create
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
                    <?php echo form_open_multipart($form['action'], ['autocomplete' => 'off', 'id' => 'form-edit-user', 'class' => '']); ?>
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Add New User</h4>
                        </div>
                        <div class="card-body">
                            <br>
                            <div class="col-12">
                                <h4 class="mb-1">
                                    <i data-feather="user" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Personal Information</span>
                                </h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['first_name']); ?>
                                    </div>
									<b class="text-danger"><?= form_error('first_name'); ?></b>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['last_name']); ?>
                                    </div>
									<b class="text-danger"><?= form_error('last_name'); ?></b>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['email']); ?>
										<b class="text-danger"><?= form_error('email'); ?></b>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['password']); ?>
										<b class="text-danger"><?= form_error('password'); ?></b>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Mobile <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['contact']); ?>
										<b class="text-danger"><?= form_error('contact'); ?></b>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Role <span class="text-danger">*</span></label>
										<?php echo form_dropdown($form['group']['name'], $form['group']['options'], $form['group']['selected'], $form['group']['attr']); ?>
										<b class="text-danger"><?= form_error('group_id'); ?></b>
									</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="submit" form="form-edit-user" class="btn btn-primary data-submit mr-1">Submit</button>
                            <a href="<?php echo site_url('user') ?>" class="btn btn-outline-secondary waves-effect" data-id="2">Back</a>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.select2').select2()
});
</script>
