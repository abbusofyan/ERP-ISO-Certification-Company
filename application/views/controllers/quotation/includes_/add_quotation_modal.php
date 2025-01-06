<div class="modal modal fade" id="add-quotation-modal">
	<div class="modal-dialog modal-dialog-centered sidebar-sm">
		<?php echo form_open($form['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-quotation']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Add Quotation</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<div class="form-group">
					<label class="form-label" for="name">Name <span class="text-danger">*</span></label>
					<?php echo form_input($form['name']); ?>
				</div>
				<button type="submit" id="submit" form="form-quotation" class="btn btn-primary data-submit mr-1">Submit</button>
				<button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
				<hr>

				<div class="notes-section"></div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
