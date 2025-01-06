<div class="tab-pane fade show active" id="poc-section" role="tabpanel">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header border-bottom">
					<h6 class="card-title">Primary Contact</h6>
					<div class="pull-right">
						<form class="form-inline">
						<label class="my-1 mr-2" for="inlineFormCustomSelectPref">Make as</label>
						<select class="form-control" id="poc-select">
							<option value="">-- select Contact --</option>
							<?php foreach ($client->contact as $contact): ?>
								<?php if ($primary_contact && $contact->id == $primary_contact->id) {
									$selected = 'selected';
								} else {
									$selected = '';
								} ?>
								<option value="<?= $contact->id ?>" <?= $selected ?>><?= $contact->salutation . ' ' . $contact->name ?>  <?= $contact->position ? "($contact->position)" : '' ?></option>
							<?php endforeach; ?>
						</select>
						</form>
					</div>
				</div>
				<div class="card-body">
					<div class="row mt-2">
						<div class="col-3">
							<p>Salutation</p>
							<b><?= $primary_contact ? $primary_contact->salutation : '' ?></b>
						</div>
						<div class="col-3">
							<p>Name</p>
							<b><?= $primary_contact ? $primary_contact->name : '' ?></b>
						</div>
						<div class="col-3">
							<p>Position</p>
							<b><?= $primary_contact ? $primary_contact->position : '' ?></b>
						</div>
						<div class="col-3">
							<p>Department</p>
							<b><?= $primary_contact ? $primary_contact->department : '' ?></b>
						</div>
					</div>
					<hr>
					<div class="row mt-2">
						<div class="col-3">
							<p>Mobile</p>
							<b><?= $primary_contact ? $primary_contact->mobile : '' ?></b>
						</div>
						<div class="col-3">
							<p>Phone (Direct)</p>
							<b><?= $primary_contact ? $primary_contact->phone : '' ?></b>
						</div>
						<div class="col-3">
							<p>Fax (Direct)</p>
							<b><?= $primary_contact ? $primary_contact->fax : '' ?></b>
						</div>
						<div class="col-3">
							<p>Email</p>
							<b><?= $primary_contact ? $primary_contact->email : '' ?></b>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="card">
				<table class="datatables-basic table datatable-poc" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/poc")); ?>" data-client-id="<?= $client->id ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
					<thead>
						<tr>
							<th data-priority="1">Salutation</th>
							<th data-priority="1">Name</th>
							<th data-priority="3">Position</th>
							<th data-priority="4">Department</th>
							<th data-priority="5">Mobile</th>
							<th data-priority="6">Phone (Direct)</th>
							<th data-priority="7">Fax (Direct)</th>
							<th data-priority="8">Email</th>
							<th data-priority="2">Action</th>
							<th data-priority="2"></th>
							<th data-priority="2" width="100"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="add-poc-form-modal">
		<div class="modal-dialog modal-lg sidebar-sm">
			<?php echo form_open_multipart($form_add_contact['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0']); ?>
				<div class="modal-header mb-1">
					<h5 class="modal-title modal-poc-title">Add New Contact</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<h5 class="mb-1">Contact Information</h5>
					<input type="hidden" value="<?= $client->id ?>" name="client_id">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="salutation">Salutation <span class="text-danger">*</span></label>
								<?php echo form_dropdown($form_add_contact['salutation']['name'], $form_add_contact['salutation']['options'], $form_add_contact['salutation']['selected'], $form_add_contact['salutation']['attr']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="status">Status <span class="text-danger">*</span></label>
								<?php echo form_dropdown($form_add_contact['status']['name'], $form_add_contact['status']['options'], $form_add_contact['status']['selected'], $form_add_contact['status']['attr']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="name">Name <span class="text-danger">*</span></label>
								<?php echo form_input($form_add_contact['name']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="email">Email <span class="text-danger">*</span></label>
								<?php echo form_input($form_add_contact['email']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="position">Position <span class="text-danger"></span></label>
								<?php echo form_input($form_add_contact['position']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="department">Department</label>
								<?php echo form_input($form_add_contact['department']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="mobile">Mobile</label>
								<?php echo form_input($form_add_contact['mobile']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="phone">Phone (Direct) <span class="text-danger"></span></label>
								<?php echo form_input($form_add_contact['phone']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="fax">Fax (Direct)<span class="text-danger"></span></label>
								<?php echo form_input($form_add_contact['fax']); ?>
							</div>
						</div>
					</div>
					<hr>
					<button type="submit" class="btn btn-primary mr-1">Add</button>
					<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>

	<div class="modal fade" id="edit-poc-form-modal">
		<div class="modal-dialog modal-lg sidebar-sm">
			<?php echo form_open_multipart($form_add_contact['action'], ['autocomplete' => 'off', 'id' => 'form-poc', 'class' => 'modal-content pt-0']); ?>
				<div class="modal-header mb-1">
					<h5 class="modal-title modal-poc-title">Edit Contact</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<h5 class="mb-1">Contact Information</h5>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="salutation">Salutation <span class="text-danger">*</span></label>
								<?php echo form_dropdown($form_add_contact['salutation']['name'], $form_add_contact['salutation']['options'], $form_add_contact['salutation']['selected'], $form_add_contact['salutation']['attr']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="status">Status <span class="text-danger">*</span></label>
								<?php echo form_dropdown($form_add_contact['status']['name'], $form_add_contact['status']['options'], $form_add_contact['status']['selected'], $form_add_contact['status']['attr']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="name">Name <span class="text-danger">*</span></label>
								<?php echo form_input($form_add_contact['name']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="email">Email <span class="text-danger">*</span></label>
								<?php echo form_input($form_add_contact['email']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="position">Position</label>
								<?php echo form_input($form_add_contact['position']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="department">Department</label>
								<?php echo form_input($form_add_contact['department']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="phone">Phone (Direct)</label>
								<?php echo form_input($form_add_contact['phone']); ?>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="fax">Fax (Direct)</label>
								<?php echo form_input($form_add_contact['fax']); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label" for="mobile">Mobile</label>
								<?php echo form_input($form_add_contact['mobile']); ?>
							</div>
						</div>
					</div>
					<hr>
					<button type="submit" class="btn btn-primary mr-1">Save Changes</button>
					<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>

</div>
