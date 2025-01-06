<div class="modal modal-slide-in fade" id="download-pdf-modal">
	<div class="modal-dialog sidebar-sm">
		<?php echo form_open(site_url('invoice/add-note'), ['autocomplete' => 'off', 'class' => 'modal-content pt-0']); ?>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title" id="exampleModalLabel">PDF Version</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<?php foreach ($invoice_history as $invoice): ?>
						<h6><b class="text-primary"><?= $invoice->number ?></b></h6>
						<h6><?= human_timestamp($invoice->created_on, 'M d, Y') ?></h6>
						<a href="<?= site_url('invoice/view_pdf/'.$invoice->id) ?>" target="_blank" class="btn btn-white btn-sm border-primary text-primary"><i data-feather="eye" class="mr-1"></i> Export PDF</a>
						<hr>
				<?php endforeach; ?>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
