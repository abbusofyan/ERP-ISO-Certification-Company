<div class="tab-pane fade" id="address-section" role="tabpanel">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<table class="datatables-basic table datatable-address" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/address")); ?>" data-client-id="<?= $client->id ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
					<thead>
						<tr>
							<th data-priority="1">Address 1</th>
							<th data-priority="3">Address 2</th>
							<th data-priority="4">Country</th>
							<th data-priority="5">Postal Code</th>
							<th data-priority="6">No Of Employee</th>
							<th data-priority="2" width="100">Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="add-address-form-modal">
		<div class="modal-dialog modal-lg sidebar-sm">
			<?php echo form_open_multipart($form_add_address['action'], ['autocomplete' => 'off', 'id' => 'form-add-address', 'class' => 'modal-content pt-0']); ?>
				<div class="modal-header mb-1">
					<h5 class="modal-title">Add New Address</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
					<input type="hidden" value="<?= $client->id ?>" name="client_id">
				</div>
				<div class="modal-body flex-grow-1">
					<div class="row pb-1">
						<div class="col-12">
							<div class="alert alert-primary alert-dismissible error-address-validation fade show" role="alert"></div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Site Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control address_name" id="selected-address-name">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>No of Employee</label>
								<input type="number" class="form-control address_total_employee" id="selected-address-no-of-employee">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Address</label>
								<input type="text" class="form-control address_address" id="selected-address-address">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Address 2</label>
								<input type="text" class="form-control address_address_2" id="selected-address-address-2">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Country</label>
								<select class="select2 address-country address_country" id="selected-address-country"></select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Postal Code</label>
								<input type="number" class="form-control address_postal_code" id="selected-address-postal-code">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Phone</label>
								<input type="number" class="form-control address_phone" id="selected-address-phone">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Fax</label>
								<input type="number" class="form-control address_fax" id="selected-address-fax">
							</div>
						</div>
					</div>
					<hr>
					<button type="button" class="btn btn-primary data-submit btn-create-address mr-1"><i data-feather="save" class="mr-1"></i> Save</button>
					<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>

	<div class="modal fade" id="edit-address-form-modal">
		<div class="modal-dialog modal-lg sidebar-sm">
			<?php echo form_open_multipart($form_add_address['action'], ['autocomplete' => 'off', 'id' => 'form-edit-address', 'class' => 'modal-content pt-0']); ?>
				<div class="modal-header mb-1">
					<h5 class="modal-title">Edit Address</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="row pb-1">
						<input type="hidden" value="" class="edit-address-id-field">
						<div class="col-12">
							<div class="alert alert-primary alert-dismissible error-edit-address-validation fade show" role="alert"></div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Site Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control edit-name-field">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>No of Employee</label>
								<input type="number" class="form-control edit-total-employee-field">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Address</label>
								<input type="text" class="form-control edit-address-field">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Address 2</label>
								<input type="text" class="form-control edit-address-2-field">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Country</label>
								<select class="select2 address-country edit-country-field"></select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Postal Code</label>
								<input type="number" class="form-control edit-postal-code-field">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Phone</label>
								<input type="number" class="form-control edit-phone-field">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Fax</label>
								<input type="number" class="form-control edit-fax-field">
							</div>
						</div>
					</div>
					<hr>
					<button type="button" class="btn btn-primary data-submit btn-update-address mr-1"><i data-feather="save" class="mr-1"></i> Save</button>
					<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>

	<!-- <div class="modal fade" id="edit-address-form-modal">
		<div class="modal-dialog modal-lg sidebar-sm">
			<?php echo form_open_multipart($form_add_address['action'], ['autocomplete' => 'off', 'id' => 'form-address', 'class' => 'modal-content pt-0']); ?>
				<div class="modal-header mb-1">
					<h5 class="modal-title">Edit Address</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_name">Site Name <span class="text-danger">*</span></label>
								<input type="text" name="name" value="" id="name" class="form-control address-input-name address-field" title="Site Name" required="1">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="total_employee">Total Employee</label>
								<?php echo form_input($form_add_address['total_employee']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address">Address</label>
								<?php echo form_input($form_add_address['address']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="address_2">Address 2</label>
								<?php echo form_input($form_add_address['address_2']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="status">Country</label>
								<?php echo form_dropdown($form_add_address['country']['name'], $form_add_address['country']['options'], $form_add_address['country']['selected'], $form_add_address['country']['attr']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">Postal Code</label>
								<?php echo form_input($form_add_address['postal_code']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="phone">Phone</label>
								<?php echo form_input($form_add_address['phone']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="fax">Fax</label>
								<?php echo form_input($form_add_address['fax']); ?>
							</div>
						</div>
					</div>
					<hr>
					<button type="submit" class="btn btn-primary data-submit btn-update-address mr-1"><i data-feather="save" class="mr-1"></i> Save</button>
					<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div> -->
</div>

<script type="text/javascript">
	var client_id = '<?= $client->id ?>'
	var data = [{
		id:'',
		text:''
	}];
	$(document).ready(function() {
		var countries = JSON.parse('<?= json_encode($countries) ?>')
		for (const key in countries) {
			data.push({
				id: countries[key].name,
				text: countries[key].name,
				title: countries[key].name,
				html: name,
			})
		}

		$(".address-country").select2({
			data: data,
			templateSelection: function(data) {
				return data.id;
			}
		})
	});

	$("#add-address-form-modal").on("hidden.bs.modal", function () {
		$('.error-address-validation').empty()
		$('.address_name').val('')
		$('.address_phone').val('')
		$('.address_fax').val('')
		$('.address_country').val('')
		$('.address_address').val('')
		$('.address_address_2').val('')
		$('.address_total_employee').val('')
		$('.address_postal_code').val('')
	});

	$('.btn-create-address').click(function() {
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/address/create")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				client_id: client_id,
				name: $('.address_name').val(),
				phone: $('.address_phone').val(),
				fax: $('.address_fax').val(),
				country: $('.address_country').val(),
				address: $('.address_address').val(),
				address_2: $('.address_address_2').val(),
				total_employee: $('.address_total_employee').val(),
				postal_code: $('.address_postal_code').val(),

			}
		}).done(function(res) {
			if (res.status) {
				$('.error-address-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Created!',
					text: 'Address has been created.',
					customClass: {
						confirmButton: 'btn btn-success'
					},
					confirmButtonText: 'Ok',
				}).then(function (result) {
					location.reload()
				});
			} else {
				var html = res.data;
				$('.error-address-validation').empty()
				$('.error-address-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	})

	$(document).on("click", ".btn-update-address", function(e) {
		$.ajax({
			beforeSend  : function(request) {
				request.setRequestHeader("X-API-KEY", "<?php echo X_API_KEY; ?>");
			},
			url: <?php echo json_encode(site_url("api/client/edit_address")); ?>,
			type: "POST",
			data: {
				<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
				address_id: $('.edit-address-id-field').val(),
				client_id: client_id,
				name: $('.edit-name-field').val(),
				phone: $('.edit-phone-field').val(),
				fax: $('.edit-fax-field').val(),
				country: $('.edit-country-field').val(),
				address: $('.edit-address-field').val(),
				address_2: $('.edit-address-2-field').val(),
				total_employee: $('.edit-total-employee-field').val(),
				postal_code: $('.edit-postal-code-field').val(),
			}
		}).done(function(res) {
			if (res.status) {
				$('.error-edit-address-validation').empty()
				$('.alert-validation').hide()
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Address Updated.',
					customClass: {
						confirmButton: 'btn btn-success'
					}
				}).then(function (result) {
					location.reload()
				});
			} else {
				var html = res.data;
				$('.error-edit-address-validation').empty()
				$('.error-edit-address-validation').append(
					'<div class="alert-body alert-validation">'+html+'</div>'
				)
			}
		})
	});

</script>
