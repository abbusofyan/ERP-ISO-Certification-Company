<div class="content-wrapper container-xxl p-0">
	<?php echo form_open_multipart(site_url('invoice/update/'.$invoice->id), ['autocomplete' => 'off']); ?>
		<div class="content-header row">
			<div class="content-header-left col-md-12 col-12 mb-2">
				<div class="d-flex justify-content-between">
					<div class="pl-2">
						<div class="row breadcrumbs-top">
							<h2 class="content-header-title float-left mb-0"><?= $invoice->number ?></h2>
							<div class="breadcrumb-wrapper">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo site_url('invoice'); ?>">Invoice</a>
									</li>
									<li class="breadcrumb-item active">Edit Invoice
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div class="p-0">
						<a href="<?= site_url('invoice') ?>" class="btn btn-light border-primary text-primary">Cancel</a>
						<button type="submit" class="btn btn-primary">Save & Request for Approval</button>
					</div>
				</div>
			</div>
		</div>
	    <div class="content-body">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<input type="hidden" name="invoice_id" value="<?= $invoice->id ?>">
						<div class="col-md-6">
							<div class="form-group">
								<label for="address">Invoice No <span class="text-danger">*</span></label>
								 <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?= $invoice->number ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_2">Invoice Date</label>
								<input type="text" class="form-control flatpickr-basic" name="invoice_date" value="<?= $invoice->invoice_date ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">Invoice For</label>
								<select class="form-control select2" name="invoice_type">
									<option value="">-- select invoice type --</option>
									<option value="Stage 1 & Stage 2 Audit" <?= $invoice->invoice_type == 'Stage 1 & Stage 2 Audit' ? 'selected' : '' ?>>Stage 1 & Stage 2 Audit</option>
									<option value="2nd Year Surveillance" <?= $invoice->invoice_type == '2nd Year Surveillance' ? 'selected' : '' ?>>2nd Year Surveillance</option>
									<option value="3rd Year Surveillance" <?= $invoice->invoice_type == '3rd Year Surveillance' ? 'selected' : '' ?>>3rd Year Surveillance</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Invoice Amount</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">$</div>
									</div>
									<input type="number" class="form-control" name="amount" value="<?= $invoice->amount ?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Audit Fix Date-Calendar</label>
								<input type="text" class="form-control flatpickr-basic" name="audit_fixed_date" value="<?= $invoice->audit_fixed_date ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Invoice Follow Up Date-Calendar</label>
								<input type="text" class="form-control flatpickr-basic" name="follow_up_date" value="<?= $invoice->follow_up_date ?>" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Invoice Status</label>
								<select class="form-control" name="status">
									<option value="">Select Status</option>
									<?php foreach ($invoice_status as $status): ?>
										<option value="<?= $status->name ?>" <?= $invoice->status == $status->name ? 'selected' : '' ?>><?= invoice_status_badge($status->name) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr>
			<h2>Client</h2><br>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<input type="hidden" class="client-id" name="client_id" value="<?= $quotation->client_history_id ?>">
						<input type="hidden" class="address-id" name="address_id" value="<?= $quotation->address_history_id ?>">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="name" value="<?= $quotation->client->name ?>">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="uen">UEN <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="uen" value="<?= $quotation->client->uen ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Address 1 <span class="text-danger">*</span></label>
								<textarea name="address" class="form-control" rows="3"><?= $quotation->address->address ?></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="uen">Address 2 </label>
								<textarea name="address_2" class="form-control" rows="3"><?= $quotation->address->address_2 ?></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Country <span class="text-danger">*</span></label>
								<select class="form-control select2" name="country">
									<?php foreach ($countries as $country): ?>
										<option value=""><?= $country->name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">Postal Code <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="postal_code" value="<?= $quotation->address->postal_code ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="phone" value="<?= $quotation->client->phone ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="fax">Fax <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="fax" value="<?= $quotation->client->fax ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="website">Website <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="website" value="<?= $quotation->client->website ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="email" value="<?= $quotation->client->email ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">No of Employee <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="total_employee" value="<?= $quotation->address->total_employee ?>">
							</div>
						</div>
					</div>
				</div>
			</div>

			<hr>
			<div class="d-flex bd-highlight">
			  <div class="flex-grow-1 bd-highlight">
				  <h2>Primary Contact</h2>
			  </div>
			  <div class="pr-1 flex-grow-1 bd-highlight">
				  <select class="form-control select-contact select2 select-select2" id="contact">
				  </select>
			  </div>
			  <div class="bd-highlight">
				  <button type="button" class="btn btn-light text-primary border-primary btn-add-contact">
				  	<i data-feather="plus" class="mr-1"></i> Add Other Contact
				  </button>
			  </div>
			</div>
			<br>
			<div class="card">
				<div class="card-body">
					<div class="row">
						<input type="hidden" name="contact_id" class="primary-contact-id" value="">
						<div class="col-md-6">
							<div class="form-group">
								<label for="salutation">Salutation <span class="text-danger">*</span></label>
								<select class="form-control primary-contact-salutation" name="">
									<?php foreach ($salutations as $salutation): ?>
										<option value="<?= $salutation->name ?>"><?= $salutation->name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Status <span class="text-danger">*</span></label>
								<select class="form-control primary-contact-status" name="">
									<option value="Active">Active</option>
									<option value="Past">Past</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control primary-contact-name" name="" value="<?= $contacts[0]->name ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="email">Email <span class="text-danger">*</span></label>
								<input type="text" class="form-control primary-contact-email" name="" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="position">Position <span class="text-danger">*</span></label>
								<input type="text" class="form-control primary-contact-position" name="" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="department">Department <span class="text-danger">*</span></label>
								<input type="text" class="form-control primary-contact-department" name="" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone (Direct)<span class="text-danger">*</span></label>
								<input type="text" class="form-control primary-contact-phone" name="" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="fax">Fax (Direct)<span class="text-danger">*</span></label>
								<input type="text" class="form-control primary-contact-fax" name="" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="mobile">Mobile <span class="text-danger">*</span></label>
								<input type="text" class="form-control primary-contact-mobile" name="" value="">
							</div>
						</div>
						<div class="col-md-12">
							<button type="button" class="btn btn-primary float-right btn-create-contact" name="button">
								<i data-feather="save" class="mr-1"></i> Save
							</button>
							<button type="button" class="btn btn-primary float-right btn-delete-contact hidden mr-1" name="button">
								<i data-feather="trash" class="mr-1"></i> Remove
							</button>
						</div>
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
								<textarea name="note" class="form-control" rows="4"></textarea>
							</div>
						</div>
						<div class="col-md-12">
							<button type="button" class="btn btn-primary float-right btn-create-contact" name="button">
								<i data-feather="send" class="mr-1"></i> Send
							</button>
						</div>
					</div>
				</div>
			</div>
	    </div>
	<?php echo form_close(); ?>
</div>

<script type="text/javascript">
$(document).ready(function () {
	$('.flatpickr-basic').flatpickr();
	$('.select2').select2()
	var contacts = JSON.parse('[{"id":"", "name":"", "position":""}]').concat(JSON.parse('<?= json_encode($contacts) ?>'))
	var primaryContact = findPrimaryContact(contacts);
	setPrimaryContactFields(primaryContact)

	function setPrimaryContactFields(primaryContact) {
		$('.primary-contact-id').val(primaryContact.id)
		$('.primary-contact-salutation').val(primaryContact.salutation)
		$('.primary-contact-status').val(primaryContact.status)
		$('.primary-contact-name').val(primaryContact.name)
		$('.primary-contact-email').val(primaryContact.email)
		$('.primary-contact-position').val(primaryContact.position)
		$('.primary-contact-department').val(primaryContact.department)
		$('.primary-contact-phone').val(primaryContact.phone)
		$('.primary-contact-fax').val(primaryContact.fax)
		$('.primary-contact-mobile').val(primaryContact.mobile)
	}

	// $("#contact").select2({
	// 	placeholder: "select contact",
	// 	data: contacts,
	// 	escapeMarkup: function(markup) {
	// 		return markup;
	// 	},
	// 	templateResult: function (d) {
	// 		return '<span>'+d.name+'</span><span class="pull-right subtext" style="float: right!important;">'+d.position+'</span>';
	// 	},
	// 	templateSelection: function() {
	// 		return '<span>'+primaryContact.name+'</span><span class="pull-right subtext" style="float: right!important;">'+primaryContact.position+'</span>';
	// 	}
	// })

	$("#contact").select2({
	    placeholder: "select contact",
	    data: contacts,
	    escapeMarkup: function(markup) {
	        return markup;
	    },
	    templateResult: function(d) {
	        if (!d.id) {
	            // Return the placeholder text for the dropdown
	            return d.text;
	        }

	        // Generate the custom label for the option
	        var label = '<span>' + d.name + '</span><span class="pull-right subtext" style="float: right!important;">' + d.position + '</span>';

	        // Return the custom label as HTML
	        return $('<div>').html(label);
	    },
	    templateSelection: function(d) {
	        if (!d.id) {
	            // Return the placeholder text for the selected option
	            return d.text;
	        }

	        // Generate the custom label for the selected option
	        var label = '<span>' + d.name + '</span><span class="pull-right subtext" style="float: right!important;">' + d.position + '</span>';

	        // Return the custom label as HTML
	        return $('<div>').html(label);
	    }
	});
	$("#contact").val(contacts[1].id).trigger('change');

	function findPrimaryContact(contacts) {
	    for (let i = 0; i < contacts.length; i++) {
	        if (contacts[i].primary === "1") {
	            return contacts[i];
	        }
	    }
	    return null; // Return null if no object with primary 1 is found
	}

	$(document).on('change', '.select-contact', function(e) {
		if ('<?= can("update-quotation") ?>') {
			var contactId = $(this).val()
			var clientId = $('.client-id').val()
			Swal.fire({
				title: 'Note!',
				text: "The selected contact will be set as primary contact",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes',
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
						url: <?php echo json_encode(site_url("api/contact/set_as_primary")); ?>,
						type: "POST",
						data: {
							<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
							contact_id: contactId,
							client_id: clientId
						}
					}).done(function(contact) {
						Swal.fire({
							icon: 'success',
							title: 'Switched!',
							text: 'Primary contact switched',
							customClass: {
								confirmButton: 'btn btn-success'
							}
						})
						$("#contact").val(contact.id)
						setPrimaryContactFields(contact)
					});
				}
			});
		} else {
			alert('Action not allowed!')
		}
	})

});
</script>
