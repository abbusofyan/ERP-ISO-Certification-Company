<div class="tab-pane fade show" id="quotation-section" role="tabpanel">

	<div class="row">
		<div class="col-12">
			<div class="card">
				<table class="datatables-basic table datatable-quotation" width="100%" data-client-id="<?= $client->id ?>" data-url="<?php echo htmlspecialchars(site_url("dt/client_quotation")); ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
					<thead>
							<tr>
									<th data-priority="1">Quote Number</th>
									<th data-priority="3">Company Name</th>
									<th data-priority="4">Certification Scheme</th>
									<th data-priority="5">Certificate Cycle</th>
									<th data-priority="6">Date Created</th>
									<th data-priority="7">Date Confirmed</th>
									<th data-priority="8">Notes</th>
									<th data-priority="2" width="100">Action</th>
							</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

</div>

<div class="modal modal-slide-in fade" id="note-modal">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open(site_url('quotation/add-note'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-quotation-note']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<input type="hidden" id="note-quotation-id" name="quotation_id">
				<div class="form-group">
					<label class="form-label" for="name">Note <span class="text-danger">*</span></label>
					<textarea name="note" cols="40" rows="3" id="note" class="form-control" required="1" placeholder="Write note here" maxlength="200"></textarea>
				</div>
				<button type="submit" id="submit" form="form-quotation-note" class="btn btn-primary data-submit mr-1">Submit</button>
				<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				<hr>

				<div class="notes-section"></div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
