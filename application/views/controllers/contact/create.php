<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Client</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('client'); ?>">Client</a>
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
                            <h4 class="card-title">Add New Client</h4>
                        </div>
                        <div class="card-body">
                            <br>
                            <div class="col-12">
                                <h4 class="mb-1">
                                    <i data-feather="user" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Company Information</span>
                                </h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">Name <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['name']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">UEN <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['uen']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Address <span class="text-danger">*</span></label>
                                        <?php echo form_textarea($form['address']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Address <small>(optional)</small> </label>
                                        <?php echo form_textarea($form['address_2']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Postal Code <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['postal_code']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Website <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['website']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Phone <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['phone']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Fax <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['fax']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Email <span class="text-danger">*</span></label>
                                        <?php echo form_input($form['email']); ?>
                                    </div>
                                </div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Status</label>
										<?php echo form_dropdown($form['status']['name'], $form['status']['options'], $form['status']['selected'], $form['status']['attr']); ?>
                                    </div>
                                </div>
                            </div>

							<br>
                            <div class="col-12">
                                <h4 class="mb-1">
                                    <i data-feather="user" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Contact Information</span><br>
									<small>*First data will be set as primary contact</small>
                                </h4>
                            </div>
							<div class="contact-template">
								<div class="row mb-5 border">
									<div class="col-md-2">
										<div class="form-group">
											<label for="salutation">Salutation <span class="text-danger">*</span></label>
											<?php echo form_dropdown($form['salutation']['name'], $form['salutation']['options'], $form['salutation']['selected'], $form['salutation']['attr']); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="name">Name <span class="text-danger">*</span></label>
											<?php echo form_input($form['contact_name']); ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="status">Status</label>
											<?php echo form_dropdown($form['contact_status']['name'], $form['contact_status']['options'], $form['contact_status']['selected'], $form['contact_status']['attr']); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="position">Position <span class="text-danger">*</span></label>
											<?php echo form_input($form['position']); ?>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="department">Department <span class="text-danger">*</span></label>
											<?php echo form_input($form['department']); ?>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="email">Email <span class="text-danger">*</span></label>
											<?php echo form_input($form['contact_email']); ?>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="mobile">Mobile <span class="text-danger">*</span></label>
											<?php echo form_input($form['contact_mobile']); ?>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="phone">Phone</label>
											<?php echo form_input($form['contact_phone']); ?>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="fax">Fax</label>
											<?php echo form_input($form['contact_fax']); ?>
										</div>
									</div>

									<div class="col-md-12">
										<button type="button" class="btn btn-primary mb-1 btn-delete-contact" name="button">Delete</button>
									</div>
								</div>
							</div>
							<div class="new-contact"></div>
							<div class="row">
                                <div class="col-lg-6">
                                    <button type="button" id="add-new-contact" class="btn btn-outline-dark round waves-effect">+ Add Contact</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="submit" form="form-edit-user" class="btn btn-primary data-submit mr-1">Save Changes</button>
                            <a href="<?php echo site_url('user') ?>" class="btn btn-outline-secondary ml-1 waves-effect" data-id="2">Back</a>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {

    // submit form
    var form = $("#form-edit-user");
    var loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ml-25 align-middle">Loading...</span>';

    $("#submit").click(function (e) {
        form.unbind("submit");
        form.submit(function (e) {
            $("#submit").html(loading).prop('disabled', true);
        });
    });

	// add contact field
	$("#add-new-contact").click(function(e) {
		var el = $('.contact-template').children().clone().find("input").val("").end().appendTo('.new-contact:last');
	});

	// delete contact row
	$(document).on("click", ".btn-delete-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var keyholder_id = $theRow.find('input#id').val();
		if (confirm("This is not reversible. Are you sure?")) {
			$theRow.remove();
		}
	});
});
</script>
