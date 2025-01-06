<div class="modal modal-slide-in fade" id="notification-modal">
	<div class="modal-dialog sidebar-sm">
		<div class="modal-content pt-0 form-filter">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			<div class="modal-header mb-1">
				<h5 class="modal-title">Notification</h5>
			</div>
			<div class="modal-body flex-grow-1">
				<?php foreach ($notifications as $invoice): ?>
					<?php if ($invoice->quotation): ?>
						<?php if ($invoice->request_status == 'Pending Update'): ?>
							<?php
								$user = $invoice->updated_by->first_name . ' ' . $invoice->updated_by->last_name;
							?>
							<p class="text-justify"><b><?= $user ?></b> has request to edit an invoice <b class="text-danger"> <?= $invoice->number ?></b><p>
						<?php else: ?>
							<?php
								$user = $invoice->user->first_name . ' ' . $invoice->user->last_name;
							 ?>
							 <p class="text-justify"><b><?= $user ?></b> has request to generate invoice for confirmed quotation <b class="text-danger"> <?= $invoice->quotation->number ?></b><p>
						<?php endif; ?>
						<button data-toggle="modal" data-target="#rejection-invoice-modal" data-notif-id="<?= $invoice->id ?>" data-url="<?= site_url('invoice/decline/'.$invoice->id) ?>" class="btn btn-light text-primary border-primary btn-sm btn-decline-invoice"><i data-feather="x"></i> Reject</button>
						<a href="<?= site_url('invoice/approve/'.$invoice->id) ?>" onclick="return confirm('Do you want to approve this invoice?');" class="btn btn-primary btn-sm"><i data-feather="check"></i> Approve</a>
						<hr>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="rejection-invoice-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Invoice Rejected</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<?php echo form_open_multipart('#', ['autocomplete' => 'off', 'id' => 'form-invoice-rejection-note']); ?>
			<div class="form-group">
				<label>Write reason here:</label>
				<textarea name="note" class="form-control" rows="5"></textarea>
			</div>
			<small>Changes written above will be automatically sync in PDF</small><br><br>
			<button type="submit" class="btn btn-primary float-right">Submit</button>
		<?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
