<div class="modal modal-slide-in fade" id="note-modal">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open($form_note['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-quotation-note']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<input type="hidden" id="note-quotation-id" name="quotation_id">
				<div class="form-group">
					<label class="form-label" for="name">Note <span class="text-danger">*</span></label>
					<?php echo form_textarea($form_note['note']); ?>
				</div>
				<button type="submit" id="submit" form="form-quotation-note" class="btn btn-primary data-submit mr-1">Submit</button>
				<button type="reset" class="btn btn-outline-secondary btn-cancel-note" data-dismiss="modal">Cancel</button>
				<hr>

				<div class="notes-section"></div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
