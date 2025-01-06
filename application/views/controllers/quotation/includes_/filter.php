<div class="modal modal-slide-in fade" id="filter-modal-slide-in">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open('#', ['autocomplete' => 'off', 'class' => 'modal-content pt-0 form-filter', 'id' => 'form-main-contact']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Filter</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<h5>Quotation Type</h5>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="quote_type_all" name="quote_type[]" value="All" />
						<label class="custom-control-label" for="quote_type_all">
							All
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input quotation-type-filter" id="ISO" name="quote_type[]" value="ISO" />
						<label class="custom-control-label" for="ISO">
							ISO
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input quotation-type-filter" id="Bizsafe" name="quote_type[]" value="Bizsafe" />
						<label class="custom-control-label" for="Bizsafe">
							Bizsafe
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input quotation-type-filter" id="Training" name="quote_type[]" value="Training" />
						<label class="custom-control-label" for="Training">
							Training
						</label>
					</div>
				</div>
				<hr>

				<h5>Certification Cycle</h5>

				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="certification_cycle_all" name="certification_cycle[]" value="All" />
						<label class="custom-control-label" for="certification_cycle_all">
							All
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input certification-cycle-filter" id="certification_cycle_new" name="certification_cycle[]" value="1" />
						<label class="custom-control-label" for="certification_cycle_new">
							New
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input certification-cycle-filter" id="Transfer-1st-Year-Surveillance" name="certification_cycle[]" value="2" />
						<label class="custom-control-label" for="Transfer-1st-Year-Surveillance">
							Transfer 1st Year Surveillance
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input certification-cycle-filter" id="Transfer-2nd-Year-Surveillance" name="certification_cycle[]" value="3" />
						<label class="custom-control-label" for="Transfer-2nd-Year-Surveillance">
							Transfer 2nd Year Surveillance
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input certification-cycle-filter" id="Re-Audit" name="certification_cycle[]" value="4" />
						<label class="custom-control-label" for="Re-Audit">
							Re-Audit
						</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input certification-cycle-filter" id="Re-Audit-New" name="certification_cycle[]" value="5" />
						<label class="custom-control-label" for="Re-Audit-New">
							Re-Audit New
						</label>
					</div>
				</div>
				<hr>

				<h5>Accreditation</h5>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="accreditation_all" name="accreditation[]" value="All" />
						<label class="custom-control-label" for="accreditation_all">
							All
						</label>
					</div>
				</div>
				<?php foreach ($accreditations as $accreditation): ?>
					<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input accreditation-filter" id="<?= $accreditation->name ?>" name="accreditation[]" value="<?= $accreditation->name ?>" />
							<label class="custom-control-label" for="<?= $accreditation->name ?>">
								<?= $accreditation->name ?>
							</label>
						</div>
					</div>
				<?php endforeach; ?>
				<hr>

				<h5>Quotation Status</h5>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="quote_status" name="quote_status[]" value="All" />
						<label class="custom-control-label" for="quote_status">
							All
						</label>
					</div>
				</div>
				<?php foreach ($quotation_status as $key => $status): ?>
					<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input quote-status-filter" id="status-<?= $key ?>" name="quote_status[]" value="<?= $status->name ?>" />
							<label class="custom-control-label" for="status-<?= $key ?>">
								<?= quotation_status_badge($status->name) ?>
							</label>
						</div>
					</div>
				<?php endforeach; ?>
				<hr>

				<h5>Client Status</h5>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="client_status_all" name="client_status[]" value="All" />
						<label class="custom-control-label" for="client_status_all">
							All
						</label>
					</div>
				</div>
				<?php foreach (constant('CLIENT_STATUS') as $key => $status): ?>
					<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input client-status-filter" id="client-status-<?= $key ?>" name="client_status[]" value="<?= $status ?>" />
							<label class="custom-control-label" for="client-status-<?= $key ?>">
								<?= client_status_badge($status); ?>
							</label>
						</div>
					</div>
				<?php endforeach; ?>
				<hr>

				<h5>Date Created</h5>
				<div class="form-group">
					<input type="text" name="date_created_start" value="" id="date_created_start" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"  />
				</div>
				<p class="text-center">--To--</p>
				<div class="form-group">
					<input type="text" name="date_created_end" value="" id="date_created_end" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD"  />
				</div>
				<hr>

				<h5>Flag</h5>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="flaged" name="flagged[]" value="1" />
						<label class="custom-control-label" for="flaged">Flaged</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="unflagged" name="flagged[]" value="0"/>
						<label class="custom-control-label" for="unflagged">Unflagged<label>
					</div>
				</div>
				<hr>

				<h5>Memo Generated</h5>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="memo-generated" name="memo_generated[]" value="1" />
						<label class="custom-control-label" for="memo-generated">Yes</label>
					</div>
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="memo-not-generated" name="memo_generated[]" value="0"/>
						<label class="custom-control-label" for="memo-not-generated">No<label>
					</div>
				</div>
				<br>
				<button class="btn btn-primary btn-filter" type="button">Apply Filter</button>
				<button class="btn btn-primary btn-clear-filter" type="button">Clear</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
