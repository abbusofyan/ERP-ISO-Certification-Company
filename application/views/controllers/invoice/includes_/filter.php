<div class="modal modal-slide-in fade" id="filter-modal">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open('#', ['autocomplete' => 'off', 'class' => 'modal-content pt-0 form-filter', 'id' => 'form-main-contact']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Filter</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<h5>Company Name</h5>
				<div class="form-group">
					<select class="form-control select2 select-select2 filter-client" name="client_id[]" multiple>
						<option value="">-- select client --</option>
						<?php foreach ($clients as $client): ?>
							<option value="<?= $client->id ?>"><?= $client->name ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<hr>

				<p><h5>Invoice Status</h5></p>

				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="invoice-status-all" name="invoice_status[]" value="All"/>
						<label class="custom-control-label" for="invoice-status-all">
							All
						</label>
					</div>
				</div>
				<?php foreach ($invoice_status as $status): ?>
					<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input filter-invoice-status" id="invoice-status-<?= $status->name ?>" name="invoice_status[]" value="<?= $status->name ?>"/>
							<label class="custom-control-label" for="invoice-status-<?= $status->name ?>">
								<?= invoice_status_badge($status->name) ?>
							</label>
						</div>
					</div>
				<?php endforeach; ?>
				<hr>

				<p><h5>Certification Cycle</h5></p>

				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="certification-cycle-all" name="certification_cycle[]" value="All"/>
						<label class="custom-control-label" for="certification-cycle-all">
							All
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input filter-certification-cycle" id="certification-cycle-new" name="certification_cycle[]" value="1"/>
						<label class="custom-control-label" for="certification-cycle-new">
							New
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input filter-certification-cycle" id="Transfer-1st-Year-Surveillance" name="certification_cycle[]" value="2" />
						<label class="custom-control-label" for="Transfer-1st-Year-Surveillance">
							Transfer 1st Year Surveillance
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input filter-certification-cycle" id="Transfer-2nd-Year-Surveillance" name="certification_cycle[]" value="3" />
						<label class="custom-control-label" for="Transfer-2nd-Year-Surveillance">
							Transfer 2nd Year Surveillance
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input filter-certification-cycle" id="Re-Audit" name="certification_cycle[]" value="4" />
						<label class="custom-control-label" for="Re-Audit">
							Re-Audit
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input filter-certification-cycle" id="Re-Audit-New" name="certification_cycle[]" value="5" />
						<label class="custom-control-label" for="Re-Audit-New">
							Re-Audit New
						</label>
					</div>
				</div>
				<hr>

				<p><h5>Date Created</h5></p>
				<div class="form-group">
					<input type="text" name="date_created_start" value="" id="date_created_start" class="form-control flatpickr-basic filter-date-created" placeholder="YYYY-MM-DD"  />
				</div>
				<p class="text-center">--To--</p>
				<div class="form-group">
					<input type="text" name="date_created_end" value="" id="date_created_end" class="form-control flatpickr-basic filter-date-created" placeholder="YYYY-MM-DD"  />
				</div>
				<hr>

				<p><h5>Month Created</h5></p>
				<div class="form-group">
					<select class="form-contorl select2 select-select2 filter-month-created" name="month_created[]" multiple title="Select Month">
						<option value="1">January</option>
						<option value="2">February</option>
						<option value="3">March</option>
						<option value="4">April</option>
						<option value="5">May</option>
						<option value="6">June</option>
						<option value="7">July</option>
						<option value="8">August</option>
						<option value="9">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
				</div>
				<hr>

				<p><h5>Year Created</h5></p>
				<div class="form-group">
					<select class="form-contorl select2 select-select2 col-12 filter-year-created" name="year_created[]" multiple title="Select Year">
						<?php for ($i=2016; $i <= date('Y') ; $i++) { ?>
							<option value="<?= $i ?>"><?= $i ?></option>
						<?php }?>
					</select>
				</div>
				<hr>

				<button class="btn btn-primary btn-filter" type="button">Apply Filter</button>
				<button class="btn btn-primary btn-clear-filter" type="button">Clear</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
