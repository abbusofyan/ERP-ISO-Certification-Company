<div class="modal modal-slide-in fade" id="memo-modal">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open($form_note['action'], ['autocomplete' => 'off', 'class' => 'modal-content pt-0']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">Memo</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<div class="memo-section"></div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
