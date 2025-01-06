<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Client</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('client'); ?>">Detail</a>
                            </li>
                            <li class="breadcrumb-item">
								Address
                            </li>
							<li class="breadcrumb-item">
								Edit
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
                            <h4 class="card-title">Edit Address</h4>
                        </div>
                        <div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="address">Address <span class="text-danger">*</span></label>
										<?php echo form_textarea($form['address']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="address_2">Address 2<small>(optional)</small> </label>
										<?php echo form_textarea($form['address_2']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="postal_code">Postal Code <span class="text-danger">*</span></label>
										<?php echo form_input($form['postal_code']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="status">Country <span class="text-danger">*</span></label>
										<?php echo form_dropdown($form['country']['name'], $form['country']['options'], $form['country']['selected'], $form['country']['attr']); ?>
									</div>
								</div>
							</div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="submit" form="form-edit-user" class="btn btn-primary data-submit mr-1">Save Changes</button>
                            <a href="<?php echo site_url('client/'.$address->client_id) ?>" class="btn btn-outline-secondary ml-1 waves-effect" data-id="2">Back</a>
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
