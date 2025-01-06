<div class="content-wrapper container-xxl p-0">
	<?php echo form_open_multipart(site_url('invoice/store'), ['autocomplete' => 'off', 'class' => 'form-invoice']); ?>
		<div class="content-header row">
			<div class="content-header-left col-md-12 col-12 mb-2">
				<div class="d-flex justify-content-between">
					<div class="pl-2">
						<div class="row breadcrumbs-top">
							<!-- <h2 class="content-header-title float-left mb-0"><?= $invoice['number'] ?></h2> -->
							<div class="breadcrumb-wrapper">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo site_url('invoice'); ?>">Invoice</a>
									</li>
									<li class="breadcrumb-item active">Create Invoice
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="p-0">
						<a href="<?= site_url('invoice') ?>" class="btn btn-light border-primary text-primary" onclick="return confirm('Are you sure you want to cancel? this invoice will be deleted');">Cancel</a>
						<button type="button" class="btn btn-primary btn-submit-invoice">Save & Request for Approval</button>
					</div>
				</div>
			</div>
		</div>
	    <div class="content-body">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="address">Quotation No <span class="text-danger">*</span></label>
								<input type="text" readonly class="form-control-plaintext" id="staticEmail" name="number" value="" hidden>
								<h3 class="mt-1"><b><?= $quotation->number ?></b></h3>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_2">Invoice Date</label>
								<input type="text" class="form-control flatpickr-basic invoice-date" name="invoice_date" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">Invoice For</label>
								<input type="text" class="form-control" name="invoice_type" value="<?= $invoice['invoice_type'] ?>" readonly>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Invoice Amount</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text"><?= $quotation->address->country == 'Singapore' ? '$' : '' ?></div>
									</div>
									<input type="number" class="form-control" name="amount" value="<?= $invoice['amount'] ?>" <?= $current_user['group_id'] != 1 ? 'readonly' : '' ?>>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Audit Fix Date-Calendar</label>
								<input type="text" class="form-control flatpickr-basic fixed-audit-date" name="audit_fixed_date" value="<?= $invoice['audit_fixed_date'] ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Invoice Status</label>
								<input type="text" class="form-control" name="status" value="New" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr>
			<h2>Client</h2><br>
			<div class="card">
				<div class="card-body">
					<div class="alert alert-primary error-client-validation alert-dismissible fade show" role="alert"></div>
					<br>
					<div class="row">
						<input type="hidden" name="quotation_id" value="<?= $quotation->id ?>">
						<input type="hidden" class="client_history_id" name="client_history_id" value="<?= $quotation->client->id ?>">
						<input type="hidden" class="client_id" value="<?= $quotation->client->client_id ?>">
						<input type="hidden" class="address_history_id" name="address_history_id" value="<?= $quotation->address->id ?>">
						<input type="hidden" class="address_id" value="<?= $quotation->address->address_id ?>">

						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="name" id="client_name" value="<?= $quotation->client->name ?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="uen">UEN <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="uen" id="client_uen" value="<?= $quotation->client->uen ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Address 1 <span class="text-danger">*</span></label>
								<textarea name="address" class="form-control" id="client_address" rows="3"><?= $quotation->address->address ?></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="uen">Address 2 </label>
								<textarea name="address_2" class="form-control" id="client_address_2" rows="3"><?= $quotation->address->address_2 ?></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Country <span class="text-danger">*</span></label>
								<select class="form-control select2" name="country" id="client_country">
									<?php foreach ($countries as $country): ?>
										<option value="<?= $country->name ?>" <?= $country->name == $quotation->address->country ? 'selected' : '' ?>><?= $country->name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">Postal Code <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="postal_code" id="client_postal_code" value="<?= $quotation->address->postal_code ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="phone" id="client_phone" value="<?= $quotation->client->phone ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="fax">Fax <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="fax" id="client_fax" value="<?= $quotation->client->fax ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="website">Website <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="website" id="client_website" value="<?= $quotation->client->website ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="email" id="client_email" value="<?= $quotation->client->email ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">No of Employee <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="total_employee" id="client_total_employee" value="<?= $quotation->address->total_employee ?>">
							</div>
						</div>
					</div>
					<button type="button" class="btn btn-primary btn-update-client float-right" name="button"><i data-feather="save" class="mr-1"></i> Update</button>
				</div>
			</div>

			<div class="contact-form">
				<div class="card">
					<div class="card-header border-bottom">
						<h4 class="card-title">Contact Details</h4>
						<button type="button" class="btn btn-primary btn-add-contact"><i data-feather="plus" class="mr-1"></i>Add Other Contact</button>
					</div>

					<div class="card-body">
						<br>
						<div class="form-group row select-contact-section">
							<label for="inputPassword" class="col-sm-2 col-form-label">Select Contact</label>
							<div class="col-sm-10">
								<select class="form-control select2 select-select2 select-contact">
									<option value="">-- Select Contact --</option>
								</select>
							</div>
						</div>

						<p class="text-danger">*First data will be set as client's primary contact</p>
						<div class="contact-form-template">
							<div class="row contact-child">
								<div class="col-12">
									<div class="alert alert-primary alert-dismissible error-contact-validation fade show" role="alert"></div>
								</div>
								<div class="contact_row" data-id="1"></div>
								<input type="hidden" name="contact_history_id" id="contact-id-1" class="contact_history_id">
								<input type="hidden" class="contact_id">
								<div class="col-md-6">
									<div class="form-group">
										<label for="salutation">Salutation <span class="text-danger">*</span></label>
										<?php echo form_dropdown($form_contact['salutation']['name'], $form_contact['salutation']['options'], $form_contact['salutation']['selected'], $form_contact['salutation']['attr']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="status">Status <span class="text-danger">*</span></label>
										<?php echo form_dropdown($form_contact['contact_status']['name'], $form_contact['contact_status']['options'], $form_contact['contact_status']['selected'], $form_contact['contact_status']['attr']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="name">Name <span class="text-danger">*</span></label>
										<?php echo form_input($form_contact['contact_name']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="email">Email <span class="text-danger">*</span></label>
										<?php echo form_input($form_contact['contact_email']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="position">Position</label>
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
										<?php echo form_input($form_contact['contact_phone']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="fax">Fax (Direct)</label>
										<?php echo form_input($form_contact['contact_fax']); ?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="mobile">Mobile</label>
										<?php echo form_input($form_contact['contact_mobile']); ?>
									</div>
								</div>
								<div class="col-md-12">
									<button type="button" class="btn btn-primary float-right btn-create-contact" name="button">
										<i data-feather="save" class="mr-1"></i> Create
									</button>
									<button type="button" class="btn btn-primary float-right btn-update-contact" name="button">
										<i data-feather="save" class="mr-1"></i> Update
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
			<h2>Notes</h2><br>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="name">Notes <span class="text-danger">*</span></label>
								<textarea name="note" class="form-control note" rows="4"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
	    </div>
	<?php echo form_close(); ?>
</div>

<script>
	$('.flatpickr-basic').flatpickr();
	$('.invoice-date').flatpickr({
		defaultDate: "today"
	});

	$('.fixed-audit-date').flatpickr({
		minDate: "today"
	});

	$('.btn-update-contact').hide();

	var client_history_id = '<?= $quotation->client_history_id ?>'
	if (client_history_id) {
		$.ajax({
			beforeSend  : function(request) {
			request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/get_detail")); ?>,
			type: "POST",
			data: {
			<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
			client_history_id: client_history_id,
			}
		}).done(function(res) {
			$('.error-client-validation').empty()
			$('.alert-validation').hide()

			$('.selected-client-detail').show()
			$('.btn-create-client').hide()
			$('.btn-update-client').show()

			var client = res.client_detail
			setSelectContactOptions(res.contacts)

			$('.contact-form-section').empty()

			$('.contact-form-template .contact_salutation').val('')
			$('.contact-form-template .contact_status').val('Active')
			$('.contact-form-template .contact_name').val('')
			$('.contact-form-template .contact_email').val('')
			$('.contact-form-template .contact_position').val('')
			$('.contact-form-template .contact_department').val('')
			$('.contact-form-template .contact_phone').val('')
			$('.contact-form-template .contact_fax').val('')
			$('.contact-form-template .contact_mobile').val('')
			$('.contact_id').val('')

		});
	} else {
		$('.btn-create-client').show()
		$('.btn-update-client').hide()

		$('.client_field').val('')
		$('#client_country').select2('val', '')
		$('.client_history_id').val('')
		$('.client_id').val('')
		$('.address_id').val('')
		$('.address_history_id').val('')
	}

	var quotation_selected_contact_history = JSON.parse('<?= json_encode($quotation->contact) ?>');
	function setSelectContactOptions(contacts) {
		var data = [];
		if(contacts.length > 0) {
			$('.select-contact-section').show()
		} else {
			$('.select-contact-section').hide()
		}

		for (const key in contacts) {
			if (contacts[key].contact_id == quotation_selected_contact_history.contact_id) {
				data.push({
					id: quotation_selected_contact_history.id,
					parent_contact_id: quotation_selected_contact_history.contact_id,
					client_id: quotation_selected_contact_history.client_id,
					text: quotation_selected_contact_history.name,
					position: quotation_selected_contact_history.position,
					title: quotation_selected_contact_history.name,
					html: quotation_selected_contact_history.name,
				})
			} else {
				data.push({
					id: contacts[key].id,
					parent_contact_id: contacts[key].contact_id,
					client_id: contacts[key].client_id,
					text: contacts[key].name,
					position: contacts[key].position,
					title: contacts[key].name,
					html: contacts[key].name,
				})
			}
		}

		var idToSelect = '<?= $quotation->contact_history_id ?>'; // Change this to the id of the item you want to select
		var itemToSelect = data.find(function(item) {
			return item.id === idToSelect;
		});

		// Set the 'selected' property of the item to true
		if (itemToSelect) {
			itemToSelect.selected = true;
		}

		$('.select-contact').find('option').not(':first').remove();
		$(".select-contact").select2({
			data: data,
				escapeMarkup: function(markup) {
				return markup;
			},
			templateResult: function (d) {
				if (d.id) {
					return '<span>'+d.text+'</span><span class="pull-right subtext" style="float: right!important;">'+d.position+'</span>';
				}
				return '<span>'+d.text+'</span>';
			},
			templateSelection: function(data) {
				return data.text;
			},
		})

		$('.select-contact').select2('val', idToSelect)
	}

	$('.selected-contact-detail').hide()
	$(document).on('change', '.select-contact', function(e) {
		var data = $('.select-contact').select2("data")[0]
		var contact_history_id = data.id
		var clientId = data.client_id

		if (!contact_history_id) {
			$('.contact-form-template .contact_salutation').val('')
			$('.contact-form-template .contact_status').val('')
			$('.contact-form-template .contact_name').val('')
			$('.contact-form-template .contact_email').val('')
			$('.contact-form-template .contact_position').val('')
			$('.contact-form-template .contact_department').val('')
			$('.contact-form-template .contact_phone').val('')
			$('.contact-form-template .contact_fax').val('')
			$('.contact-form-template .contact_mobile').val('')
			$('.contact_id').val('')
			$('.contact_history_id').val('')

			$('.btn-create-contact').show()
			$('.btn-update-contact').hide()
			return;
		}

		$('.error-contact-validation').empty()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/get_detail")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				contact_history_id: contact_history_id,
			}
		}).done(function(res) {
			var contact = res.contact_detail
			if (contact) {
				$('.contact-form-template .contact_salutation').val(contact ? contact.salutation : '')
				$('.contact-form-template .contact_status').val(contact ? contact.status : '')
				$('.contact-form-template .contact_name').val(contact ? contact.name : '')
				$('.contact-form-template .contact_email').val(contact ? contact.email : '')
				$('.contact-form-template .contact_position').val(contact ? contact.position : '')
				$('.contact-form-template .contact_department').val(contact ? contact.department : '')
				$('.contact-form-template .contact_phone').val(contact ? contact.phone : '')
				$('.contact-form-template .contact_fax').val(contact ? contact.fax : '')
				$('.contact-form-template .contact_mobile').val(contact ? contact.mobile : '')
				$('.contact_id').val(contact ? contact.contact_id : '')
				$('.contact_history_id').val(contact ? contact.id : '')

				$('.btn-create-contact').hide()
				$('.btn-update-contact').show()
			}

		});
	})

	var row_contact = 1;
	$(document).on('click', '.btn-add-contact', function(e) {
		if (client_status != 'New' && row_contact == 0) {
			$('.create-contact-form').removeClass('hidden')
			row_contact = $('.contact-child').length + 1
		} else {
			row_contact = $('.contact-child').length + 1
			var el = $('.contact-form-template').children().clone()
					.find('input').attr('readonly', false).end()
					.find('.contact_salutation').attr('readonly', false).end()
					.find('.contact_status').attr('readonly', false).end()
					.find('.btn-delete-contact').removeClass('hidden').end()
					.find('.btn-create-contact').removeClass('hidden').end()
					.find('.btn-create-contact').show().end()
					.find('.btn-update-contact').hide().end()
					.find('.contact_history_id').remove().end()
					.find('.error-contact-validation').empty().end()
					.find("input").val("").end().append('<hr>')
					.find(".contact_row").attr('data-id', row_contact).end()
					.append('<hr>')
					.appendTo('.contact-form-section:last');
		}
		$('.selected-contact-detail').hide()
	})

	$(document).on("click", ".btn-create-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var row_contact = $theRow.find('.contact_row').data('id')
		var contact_id = $theRow.find('.contact_id').val()
		var primary = row_contact == 1 ? 1 : 0;
		var client_id = $('.client_id').val()

		if (!client_id) {
			Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Please create or select a client first!',
				customClass: {
					confirmButton: 'btn btn-danger'
				}
			})
			return;
		}
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/create")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				salutation: $theRow.find('.contact_salutation').val(),
				name: $theRow.find('.contact_name').val(),
				status: $theRow.find('.contact_status').val(),
				position: $theRow.find('.contact_position').val(),
				department: $theRow.find('.contact_department').val(),
				email: $theRow.find('.contact_email').val(),
				mobile: $theRow.find('.contact_mobile').val(),
				phone: $theRow.find('.contact_phone').val(),
				fax: $theRow.find('.contact_fax').val(),
				primary: primary
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-contact-validation').empty()
				Swal.fire({
					icon: 'success',
					title: 'Created!',
					text: 'Contact has been created.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$theRow.find('.contact_id').val(res.data.contact_id)
				$theRow.find('.contact_history_id').val(res.data.contact_history_id)
				$theRow.find('.btn-create-contact').addClass('hidden')
				$theRow.find('.btn-delete-contact').addClass('hidden')
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

	$(document).on("click", ".btn-update-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var row_contact = $theRow.find('.contact_row').data('id')
		var primary = row_contact == 1 ? 1 : 0;
		var client_id = $('.client_id').val()

		if (!client_id) {
			return Swal.fire({
				icon: 'error',
				title: 'Error!',
				text: 'Please create or select a client first!',
				customClass: {
					confirmButton: 'btn btn-danger'
				}
			})
		}
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				contact_id: $theRow.find('.contact_id').val(),
				salutation: $theRow.find('.contact_salutation').val(),
				name: $theRow.find('.contact_name').val(),
				status: $theRow.find('.contact_status').val(),
				position: $theRow.find('.contact_position').val(),
				department: $theRow.find('.contact_department').val(),
				email: $theRow.find('.contact_email').val(),
				mobile: $theRow.find('.contact_mobile').val(),
				phone: $theRow.find('.contact_phone').val(),
				fax: $theRow.find('.contact_fax').val(),
				primary: primary
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-contact-validation').empty()
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Contact has been updated.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$theRow.find('.contact_history_id').val(res.data.contact_history_id)
			} else {
				var html = res.data;
				$theRow.find('.error-contact-validation').empty()
				$theRow.find('.error-contact-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	});

	$(document).on("click", ".btn-delete-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		if (confirm("This is not reversible. Are you sure?")) {
			if ($theRow.find('.contact_history_id').val()) {
				$theRow.find('input').val('')
				$theRow.find('.contact_salutation').val('')
				$theRow.find('.contact_status').val('Active')
				$theRow.find('.btn-update-contact').hide()
				$theRow.find('.btn-create-contact').show()
				$theRow.find('.contact_status').val('Active')
				$('.select-contact').select2('val', '')
			} else {
				$theRow.remove();
				row_contact--;
				if (row_contact == 1) {
				$('.add-contact-active-client').hide()
				}
			}
		}
	});

	$(document).on("click", ".btn-submit-invoice", function(e) {
		validateForm()
	});

	function validateForm() {
		var errors = [];

		var contact_history_id = $('.contact_history_id').val()
		if (!contact_history_id) {
			errors.push('- Create or select a primary contact<br>')
		}

		var input = $('.form-training :input').filter(function() { return this.value === ""; });

		input.each(function() {
			if (this.required) {
				if ($(this).is(":visible")) {
					errors.push('- Please enter ' + this.title + '<br>')
				}
			}
		});

		if (errors.length > 0) {
			return toastr.error(errors, 'Error validation form')
		} else {
			$( ".form-invoice" ).submit();
		}
	}

	$(document).on('click', '.btn-update-client', function() {
		var address_id = $('.address_id').val()
		var client_id = $('.client_id').val()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				address_id: address_id,
				name: $('#client_name').val(),
				uen: $('#client_uen').val(),
				address: $('#client_address').val(),
				address_2: $('#client_address_2').val(),
				country: $('#client_country').val(),
				postal_code: $('#client_postal_code').val(),
				phone: $('#client_phone').val(),
				fax: $('#client_fax').val(),
				website: $('#client_website').val(),
				email: $('#client_email').val(),
				total_employee: $('#client_total_employee').val(),
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-client-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Client updated.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$('.client_history_id').val(res.data.client_history_id)
				$('.address_history_id').val(res.data.address_history_id)
			} else {
				var html = res.data;
				$('.error-client-validation').empty()
				$('.error-client-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		});
	})
</script>

<!-- <script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();
	$('.select2').select2()
	$('.invoice-date').flatpickr({
		defaultDate: "today"
	});
	var contacts = JSON.parse('[{"id":"", "name":"", "position":""}]').concat(JSON.parse('<?= json_encode($contacts) ?>'))

	$(".select-contact").select2({
	    placeholder: "select contact",
	    data: contacts,
	    escapeMarkup: function(markup) {
	        return markup;
	    },
	    templateResult: function(d) {
	        if (!d.id) {
	            return d.text;
	        }
	        var label = '<span>' + d.name + '</span><span class="pull-right subtext" style="float: right!important;">' + d.position + '</span>';
	        return $('<div>').html(label);
	    },
	    templateSelection: function(d) {
	        if (!d.id) {
	            return d.text;
	        }
	        var label = '<span>' + d.name + '</span><span class="pull-right subtext" style="float: right!important;">' + d.position + '</span>';
	        return $('<div>').html(label);
	    }
	});
	// $(".select-contact").val(contacts[1].id).trigger('change');
	$('.btn-update-contact').hide();
	$(document).on('change', '.select-contact', function(e) {
		var data = $(this).select2("data")[0]
		var primary = data.is_primary
		var contact_history_id = data.id
		var clientId = data.client_id

		if (!contact_history_id) {
			$('.contact-form-template .contact_salutation').val('')
			$('.contact-form-template .contact_status').val('')
			$('.contact-form-template .contact_name').val('')
			$('.contact-form-template .contact_email').val('')
			$('.contact-form-template .contact_position').val('')
			$('.contact-form-template .contact_department').val('')
			$('.contact-form-template .contact_phone').val('')
			$('.contact-form-template .contact_fax').val('')
			$('.contact-form-template .contact_mobile').val('')
			$('.contact_id').val('')
			$('.contact_history_id').val('')

			$('.btn-create-contact').show()
			$('.btn-update-contact').hide()
			return;
		}

		$('.error-contact-validation').empty()

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/get_detail")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				contact_history_id: contact_history_id,
			}
		}).done(function(res) {
			var contact = res.contact_detail
			if (contact) {
				$('.contact-form-template .contact_salutation').val(contact ? contact.salutation : '')
				$('.contact-form-template .contact_status').val(contact ? contact.status : '')
				$('.contact-form-template .contact_name').val(contact ? contact.name : '')
				$('.contact-form-template .contact_email').val(contact ? contact.email : '')
				$('.contact-form-template .contact_position').val(contact ? contact.position : '')
				$('.contact-form-template .contact_department').val(contact ? contact.department : '')
				$('.contact-form-template .contact_phone').val(contact ? contact.phone : '')
				$('.contact-form-template .contact_fax').val(contact ? contact.fax : '')
				$('.contact-form-template .contact_mobile').val(contact ? contact.mobile : '')
				$('.contact_id').val(contact ? contact.contact_id : '')
				$('.contact_history_id').val(contact ? contact.id : '')

				$('.btn-create-contact').hide()
				$('.btn-update-contact').show()
			}

			// if (!contact) {
			// 	$(".select-contact").select2("val", previous_selected_contact);
			// }
		});
	})


	var row_contact = 0;
	$(document).on('click', '.btn-add-contact', function(e) {
		row_contact = $('.contact-child').length + 1
			var el = $('.contact-form-template').children().clone()
					.find('input').attr('readonly', false).end()
					.find('.contact_salutation').attr('readonly', false).end()
					.find('.contact_status').attr('readonly', false).end()
					.find('.btn-delete-contact').removeClass('hidden').end()
					.find('.btn-create-contact').removeClass('hidden').end()
					.find('.btn-create-contact').show().end()
					.find('.btn-update-contact').hide().end()
					.find('.contact_history_id').remove().end()
					.find('.error-contact-validation').empty().end()
					.find("input").val("").end().append('<hr>')
					.find(".contact_row").attr('data-id', row_contact).end()
					.append('<hr>')
					.appendTo('.contact-form-section:last');
		$('.selected-contact-detail').hide()
		// $('.select-contact-form').find('select').select2('val', '')
	})

	$(document).on("click", ".btn-create-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		var row_contact = $theRow.find('.contact_row').data('id')
		var primary = row_contact == 1 ? 1 : 0;
		var client_id = '<?= $quotation->client->client_id ?>'

		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/contact/create")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				salutation: $theRow.find('.contact_salutation').val(),
				name: $theRow.find('.contact_name').val(),
				status: $theRow.find('.contact_status').val(),
				position: $theRow.find('.contact_position').val(),
				department: $theRow.find('.contact_department').val(),
				email: $theRow.find('.contact_email').val(),
				mobile: $theRow.find('.contact_mobile').val(),
				phone: $theRow.find('.contact_phone').val(),
				fax: $theRow.find('.contact_fax').val(),
				primary: primary
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-contact-validation').empty()
				Swal.fire({
					icon: 'success',
					title: 'Created!',
					text: 'Contact has been created.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$theRow.find('.contact_id').val(res.data.contact_id)
				$theRow.find('.btn-create-contact').addClass('hidden')
				$theRow.find('.btn-delete-contact').addClass('hidden')
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

	$(document).on("click", ".btn-delete-contact", function(e) {
		var $theRow = $(e.target).closest("div.row");
		if (confirm("This is not reversible. Are you sure?")) {
			$theRow.remove();
		}

		row_contact--;
		if (row_contact == 1) {
			$('.add-contact-active-client').hide()
		}
	});

	$(document).on('click', '.btn-update-client', function() {
		var address_id = $('.address_id').val()
		var client_id = $('.client_id').val()
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/update")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				address_id: address_id,
				name: $('#client_name').val(),
				uen: $('#client_uen').val(),
				address: $('#client_address').val(),
				address_2: $('#client_address_2').val(),
				country: $('#client_country').val(),
				postal_code: $('#client_postal_code').val(),
				phone: $('#client_phone').val(),
				fax: $('#client_fax').val(),
				website: $('#client_website').val(),
				email: $('#client_email').val(),
				total_employee: $('#client_total_employee').val(),
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-client-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Client updated.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				})
				$('.client_history_id').val(res.data.client_history_id)
				$('.address_history_id').val(res.data.address_history_id)
			} else {
				var html = res.data;
				$('.error-client-validation').empty()
				$('.error-client-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		});
	})

	$(document).on("click", ".btn-submit-invoice", function(e) {
		validateForm()
	});

	function validateForm() {
		var errors = [];

		var contact_history_id = $('.contact_history_id').val()
		if (!contact_history_id) {
			errors.push('- Create or select a primary contact<br>')
		}

		var input = $('.form-training :input').filter(function() { return this.value === ""; });

		input.each(function() {
			if (this.required) {
				if ($(this).is(":visible")) {
					errors.push('- Please enter ' + this.title + '<br>')
				}
			}
		});

		if (errors.length > 0) {
			return toastr.error(errors, 'Error validation form')
		} else {
			$( ".form-invoice" ).submit();
		}
	}

});
</script> -->
