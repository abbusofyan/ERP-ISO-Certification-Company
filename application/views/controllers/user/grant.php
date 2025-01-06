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
								Grand Access
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
                    <?php echo form_open_multipart('User/grant', ['autocomplete' => 'off', 'id' => 'form-edit-user', 'class' => '']); ?>
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Grant Access</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">IP <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="ip" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Owner <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="role" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="submit" form="form-edit-user" class="btn btn-primary data-submit mr-1">Submit</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
