<div class="content-wrapper container-xxl p-0">
	<?php echo form_open_multipart('client/create', ['autocomplete' => 'off']); ?>
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Add Client</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo site_url('client'); ?>">Client</a>
                            </li>
                            <li class="breadcrumb-item">
								add client
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-md-3 float-right">
			<button type="submit" class="btn btn-primary float-right ml-1">Save</button>
			<a href="<?php echo site_url('client') ?>" class="btn btn-outline-secondary waves-effect float-right">Cancel</a>
		</div>
    </div>
    <div class="content-body">
		<div class="card">
			<div class="card-body">
				<div class="alert alert-primary error-client-validation alert-dismissible fade show" role="alert"></div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Name <span class="text-danger">*</span></label>
							<?php echo form_input($form_client['name']); ?>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="uen">UEN <span class="text-danger">*</span></label>
							<?php echo form_input($form_client['uen']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="uen">Address <span class="text-danger">*</span></label>
							<?php echo form_textarea($form_client['address']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="uen">Address 2 <span class="text-danger">*</span></label>
							<?php echo form_textarea($form_client['address_2']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="status">Country <span class="text-danger">*</span></label>
							<?php echo form_dropdown($form_client['country']['name'], $form_client['country']['options'], $form_client['country']['selected'], $form_client['country']['attr']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="uen">Postal Code <span class="text-danger">*</span></label>
							<?php echo form_input($form_client['postal_code']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="phone">Phone </label>
							<?php echo form_input($form_client['phone']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="fax">Fax </label>
							<?php echo form_input($form_client['fax']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="website">Website </label>
							<?php echo form_input($form_client['website']); ?>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="email">Email </label>
							<?php echo form_input($form_client['email']); ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="postal_code">No of Employee <span class="text-danger">*</span></label>
							<?php echo form_input($form_client['total_employee']); ?>
						</div>
					</div>
					<input type="hidden" class="client_id" name="client_id" value="">
					<div class="col-md-12">
						<button type="button" class="btn btn-primary mb-1 btn-create-client float-right" name="button">
							<i data-feather="save" class="mr-1"></i> Save
						</button>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="d-flex justify-content-between align-items-center">
			<h2 class="m-0">Contacts</h2>
			<button type="button" class="btn btn-primary btn-add-contact float-right mb-2">
				<i data-feather="plus" class="font-medium-4 mr-25"></i>
				Add Other Contact
			</button>
		</div>
		<div class="card">
			<div class="contact-form create-contact-form">
				<div class="card-body">
					<p>*First data will be set as primary contact</p>
					<div class="contact-form-template mt-0">
						<div class="row contact-child">
							<div class="col-12">
								<div class="alert alert-primary alert-dismissible error-contact-validation fade show" role="alert"></div>
							</div>
							<div class="contact_row" data-id="1"></div>
							<input type="hidden" name="contact_history_id" id="contact-id-1" class="contact_id">
							<div class="col-md-6">
								<div class="form-group">
									<label for="salutation">Salutation <span class="text-danger">*</span></label>
									<?php echo form_dropdown($form_contact['salutation']['name'], $form_contact['salutation']['options'], $form_contact['salutation']['selected'], $form_contact['salutation']['attr']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="status">Status <span class="text-danger">*</span></label>
									<?php echo form_dropdown($form_contact['status']['name'], $form_contact['status']['options'], $form_contact['status']['selected'], $form_contact['status']['attr']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="name">Name <span class="text-danger">*</span></label>
									<?php echo form_input($form_contact['name']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email">Email <span class="text-danger">*</span></label>
									<?php echo form_input($form_contact['email']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="position">Position </label>
									<?php echo form_input($form_contact['position']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="department">Department </label>
									<?php echo form_input($form_contact['department']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="phone">Phone (Direct)</label>
									<?php echo form_input($form_contact['phone']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="fax">Fax (Direct)</label>
									<?php echo form_input($form_contact['fax']); ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="mobile">Mobile </label>
									<?php echo form_input($form_contact['mobile']); ?>
								</div>
							</div>
							<div class="col-md-12">
								<button type="button" class="btn btn-primary float-right btn-create-contact" name="button">
									<i data-feather="save" class="mr-1"></i> Save
								</button>
								<button type="button" class="btn btn-primary float-right btn-delete-contact mr-1" name="button">
									<i data-feather="trash" class="mr-1"></i> Remove
								</button>
							</div>
						</div>
					</div>
					<hr>
					<div class="contact-form-section mb-3"></div>
				</div>
			</div>
		</div>
		<hr>
		<div class="d-flex justify-content-between align-items-center">
			<h2 class="m-0">Sites</h2>
			<button type="button" class="btn btn-primary btn-add-address float-right mb-2">
				<i data-feather="plus" class="font-medium-4 mr-25"></i>
				Add Site
			</button>
		</div>
		<div class="card address-section">
			<div class="card-body">
				<div class="address-form-template">
					<div class="row address-child">
						<div class="col-md-12">
							<div class="alert alert-primary alert-dismissible error-address-validation fade show" role="alert"></div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_name">Name <span class="text-danger">*</span></label>
								<?php echo form_input($form_address['name']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_phone">Phone <span class="text-danger">*</span></label>
								<?php echo form_input($form_address['phone']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_fax">Fax <span class="text-danger">*</span></label>
								<?php echo form_input($form_address['fax']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Country <span class="text-danger">*</span></label>
								<?php echo form_dropdown($form_address['country']['name'], $form_address['country']['options'], $form_address['country']['selected'], $form_address['country']['attr']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address">Address <span class="text-danger">*</span></label>
								<?php echo form_textarea($form_address['address']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_2">Address 2<small>(optional)</small> </label>
								<?php echo form_textarea($form_address['address_2']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">Postal Code <span class="text-danger">*</span></label>
								<?php echo form_input($form_address['postal_code']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">No of Employee <span class="text-danger">*</span></label>
								<?php echo form_input($form_address['total_employee']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<button type="button" class="btn btn-primary float-right btn-create-address" name="button">
								<i data-feather="save" class="mr-1"></i> Save
							</button>
							<button type="button" class="btn btn-primary float-right btn-delete-address mr-1" name="button">
								<i data-feather="trash" class="mr-1"></i> Remove
							</button>
						</div>
					</div>
				</div>
				<div class="address-form-section"></div>
			</div>
		</div>
    </div>
	<?php echo form_close(); ?>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.select2').select2();

	$(document).on("click", ".btn-create-client", function(e) {
		var data = {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			name: $('#client_name').val(),
			uen: $('#client_uen').val(),
			website: $('#client_website').val(),
			phone: $('#client_phone').val(),
			fax: $('#client_fax').val(),
			email: $('#client_email').val(),
			address: $('#client_address').val(),
			address_2: $('#client_address_2').val(),
			postal_code: $('#client_postal_code').val(),
			country: $('#client_country').val(),
			total_employee: $('#client_total_employee').val(),
		};

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/create")); ?>,
			type: "POST",
			data: data
		}).done(function(res) {
			if (res.status) {
				$('.error-client-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Created!',
					text: 'Client has been created.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$('.client_id').val(res.data.client_id)
				$('.btn-create-client').hide()
				$('.create_client_field').attr('readonly', true)
				$('#client_country').select2({
					disabled: true
				});
			} else {
				var html = res.data;
				$('.error-client-validation').empty()
				$('.error-client-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	});


	$('.btn-delete-contact').hide()
	var row_contact = 1;
	$(document).on('click', '.btn-add-contact', function(e) {
		row_contact = $('.contact-child').length + 1
		var el = $('.contact-form-template').children().clone()
				.find('input').attr('readonly', false).end()
				.find('.poc-select-salutation').attr('readonly', false).end()
				.find('.poc-select-status').attr('readonly', false).end()
				.find('.btn-delete-contact').show().end()
				.find('.btn-create-contact').show().end()
				.find('.contact_id').attr('name', '').end()
				.find('.error-contact-validation').empty().end()
				.find("input").val("").end().append('<hr>')
				.find(".contact_row").attr('data-id', row_contact).end()
				.append('<hr>')
				.appendTo('.contact-form-section:last');
	})


	$(document).on("click", ".btn-create-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var row_contact = $theRow.find('.contact_row').data('id')
		var primary = row_contact == 1 ? 1 : 0;
		var client_id = $('.client_id').val()

		if (!client_id) {
			Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Save client data first!',
				customClass: {
					confirmButton: 'btn btn-danger'
				}
			})
			return;
		}
		var data = {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			client_id: client_id,
			salutation: $theRow.find('.poc-select-salutation').val(),
			name: $theRow.find('.poc-input-name').val(),
			status: $theRow.find('.poc-select-status').val(),
			position: $theRow.find('.poc-input-position').val(),
			department: $theRow.find('.poc-input-department').val(),
			email: $theRow.find('.poc-input-email').val(),
			mobile: $theRow.find('.poc-input-mobile').val(),
			phone: $theRow.find('.poc-input-phone').val(),
			fax: $theRow.find('.poc-input-fax').val(),
			primary: primary
		};
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/create")); ?>,
			type: "POST",
			data: data
		}).done(function(res) {
			if (res.status) {
				$('.error-client-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Created!',
					text: 'Contact has been created.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$theRow.find('.btn-create-contact').hide()
				$theRow.find('.btn-delete-contact').hide()
				$theRow.find('.create_contact_field').attr('readonly', true)
			} else {
				var html = res.data;
				$theRow.find('.error-contact-validation').empty()
				$theRow.find('.error-contact-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	});

	$('.address-form-template').hide();
	$('.address-section').hide();
	$(document).on("click", ".btn-delete-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var row_address = $('#number-of-sites').val() - 1
		if (confirm("This is not reversible. Are you sure?")) {
			$theRow.remove();
		}
	});


	// add new address
	var row_address = 0;
	$(document).on('click', '.btn-add-address', function(e) {
		row_address = $('.contact-child').length + 1
		$('.address-section').show()
		var el = $('.address-form-template').children().clone()
					.find("input").val("").end()
					.find('.address-select-country').select2().end()
					.append('<hr>')
					.append('<hr>').appendTo('.address-form-section:last');
	})


	// create new contact
	$(document).on("click", ".btn-create-address", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var client_id = $('.client_id').val()
		if (!client_id) {
			Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Please create a new client first!',
				customClass: {
					confirmButton: 'btn btn-danger'
				}
			})
			return;
		}
		var data = {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			client_id: client_id,
			address_name: $theRow.find('.address-input-name').val(),
			phone: $theRow.find('.address-input-phone').val(),
			fax: $theRow.find('.address-input-fax').val(),
			country: $theRow.find('.address-select-country').val(),
			address: $theRow.find('.address-input-address').val(),
			address_2: $theRow.find('.address-input-address2').val(),
			total_employee: $theRow.find('.address-input-total-employee').val(),
			postal_code: $theRow.find('.address-input-postal-code').val(),
		}
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/address/create")); ?>,
			type: "POST",
			data: data
		}).done(function(res) {
			if (res.status) {
				$theRow.find('.error-address-validation').empty()
				$theRow.find('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Created!',
					text: 'Address has been created.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$theRow.find('.btn-create-address').hide()
				$theRow.find('.btn-delete-address').hide()
				$theRow.find('.address-field').attr('readonly', true)
				$theRow.find('.address-select-country').select2({
					disabled: true
				});
			} else {
				var html = res.data;
				$theRow.find('.error-address-validation').empty()
				$theRow.find('.error-address-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	});



	// delete address field
	$(document).on("click", ".btn-delete-address", function(e) {
		var $theRow = $(e.target).closest("div.row");
		row_address--;
		if (confirm("This is not reversible. Are you sure?")) {
			$theRow.remove();
		}
		if (row_address == 0) {
			$('.address-section').hide()
		}
	});

	$('#client_country').change(function() {
		if ($(this).val() == 'Singapore') {
			$('#client_postal_code').attr('type', 'number')
		} else {
			$('#client_postal_code').attr('type', 'text')
		}
	})


});
</script>
