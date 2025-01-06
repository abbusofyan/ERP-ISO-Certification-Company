<div class="contact-form hidden">
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
							<i data-feather="save" class="mr-1"></i> Save Changes
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
