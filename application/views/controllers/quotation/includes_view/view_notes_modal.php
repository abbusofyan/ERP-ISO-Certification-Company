<div class="modal modal-slide-in fade" id="view-notes-modal">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open(site_url('quotation/add-note'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0', 'id' => 'form-quotation-note']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<input type="hidden" id="note-quotation-id" name="quotation_id" value="<?= $quotation->id ?>">
				<div class="form-group">
					<label class="form-label" for="name">Note <span class="text-danger">*</span></label>
					<textarea name="note" class="form-control note" id="" cols="30" rows="4"></textarea>
				</div>
				<button type="submit" id="submit" form="form-quotation-note" class="btn btn-primary data-submit mr-1">Submit</button>
				<button type="reset" class="btn btn-outline-secondary btn-cancel-note" data-dismiss="modal">Cancel</button>
				<hr>

				<?php foreach ($quotation_notes as $note) { ?>
					<div class="d-flex bd-highlight">
						<div class="pr-1 flex-shrink-1 bd-highlight">
							<img class="img-fluid" src="<?= assets_url('img/blank-profile.png') ?>" width="50" alt="">
						</div>
						<div class="w-100 bd-highlight">
							<b><?= $note->created_by->first_name . ' ' . $note->created_by->last_name ?></b><br>
							<p><?= $note->created_by->group->name ?></p>
						</div>
					</div>
					<span><?= nl2br($note->note) ?><br><br><?= human_timestamp($note->created_on) ?><hr></span>
				<?php }?>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
