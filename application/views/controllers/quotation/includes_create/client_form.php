<input type="hidden" class="client_history_id" name="client_history_id">
<input type="hidden" class="client_id">
<input type="hidden" class="address_history_id" name="address_history_id">
<input type="hidden" class="address_id">

<div class="client-form hidden">
	<div class="card">
		<div class="card-header border-bottom">
			<h4 class="card-title">Client Details</h4>
		</div>
		<div class="card-body">
			<div class="alert alert-primary error-client-validation alert-dismissible fade show" role="alert"></div>
			<br>
			<div class="form-group row select-client-section">
				<label for="inputPassword" class="col-sm-2 col-form-label">Select Client</label>
				<div class="col-sm-10">
					<select class="form-control select2 select-select2 select-client">
						<option value="">-- Select Client --</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="name">Name <span class="text-danger">*</span></label>
						<?php echo form_input($form_client['client_name']); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="uen">UEN <span class="text-danger">*</span></label>
						<?php echo form_input($form_client['client_uen']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="name">Address 1 <span class="text-danger">*</span></label>
						<?php echo form_textarea($form_client['client_address']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="uen">Address 2 <span class="text-danger">*</span></label>
						<?php echo form_textarea($form_client['client_address_2']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="status">Country <span class="text-danger">*</span></label>
						<?php echo form_dropdown($form_client['client_country']['name'], $form_client['client_country']['options'], false, $form_client['client_country']['attr']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="postal_code">Postal Code <span class="text-danger">*</span></label>
						<?php echo form_input($form_client['client_postal_code']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="phone">Phone</label>
						<?php echo form_input($form_client['client_phone']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="fax">Fax</label>
						<?php echo form_input($form_client['client_fax']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="website">Website</label>
						<?php echo form_input($form_client['client_website']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="email">Email </label>
						<?php echo form_input($form_client['client_email']); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="postal_code">No of Employee <span class="text-danger">*</span></label>
						<?php echo form_input($form_client['client_total_employee']); ?>
					</div>
				</div>
				<div class="col-md-12">
					<button type="button" class="btn btn-primary btn-update-client float-right" name="button"><i data-feather="save" class="mr-1"></i> Save Changes</button>
					<button type="button" class="btn btn-primary btn-create-client float-right" name="button"><i data-feather="save" class="mr-1"></i> Create</button>
				</div>
			</div>
		</div>
	</div>
</div>
