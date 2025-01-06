<div class="card mt-2">
	<div class="card-header border-bottom">
		<h4 class="card-title">Contact</h4>
	</div>
	<div class="card-body">
		<div class="alert alert-primary error-client-validation alert-dismissible fade show" role="alert"></div>
		<br>
		<div class="row">
			<input type="hidden" name="contact_history_id" class="contact_id" value="<?= $quotation->contact_history_id ?>">
			<div class="col-md-6">
				<div class="form-group">
					<label for="salutation">Salutation <span class="text-danger">*</span></label>
					<select class="form-control select2 select-select2 salutation">
						<?php foreach ($salutations as $salutation): ?>
							<option value="<?= $salutation->name ?>"><?= $salutation->name ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="status">Status <span class="text-danger">*</span></label>
					<select class="form-control select2 select-select2 status">
						<?php foreach (constant('CONTACT_STATUS') as $status): ?>
							<option value="<?= $status ?>" <?= $status == $quotation->contact->status ? 'selected' : '' ?>><?= $status ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="name">Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control name" value="<?= $quotation->contact->name ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="email">Email <span class="text-danger">*</span></label>
					<input type="text" class="form-control email" value="<?= $quotation->contact->email ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="position">Position <span class="text-danger">*</span></label>
					<input type="text" class="form-control position" value="<?= $quotation->contact->position ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="department">Department <span class="text-danger">*</span></label>
					<input type="text" class="form-control department" value="<?= $quotation->contact->department ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="phone">Phone <span class="text-danger">*</span></label>
					<input type="text" class="form-control phone" value="<?= $quotation->contact->phone ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="fax">Fax <span class="text-danger">*</span></label>
					<input type="text" class="form-control fax" value="<?= $quotation->contact->fax ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="mobile">Mobile <span class="text-danger">*</span></label>
					<input type="text" class="form-control mobile" value="<?= $quotation->contact->mobile ?>">
				</div>
			</div>
			<div class="col-md-12">
				<button type="button" class="btn btn-primary float-right btn-update-contact" name="button">
					<i data-feather="save" class="mr-1"></i> Save Changes
				</button>
			</div>
		</div>
	</div>
</div>
