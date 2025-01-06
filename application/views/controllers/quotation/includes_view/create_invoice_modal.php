<div class="modal fade" id="create-invoice-modal">
	<div class="modal-dialog modal-dialog-centered sidebar-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Generate Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			</div>
			<div class="modal-body">
				<?php echo form_open_multipart(site_url('invoice/create'), ['autocomplete' => 'off', 'id' => 'form-create-invoice', 'class' => 'create-invoice-form']); ?>
					<input type="hidden" name="quotation_id" value="<?= $quotation->id ?>">
					<input type="hidden" name="client_id" value="<?= $quotation->client_history_id ?>">
					<input type="hidden" name="address_id" value="<?= $quotation->address_history_id ?>">
					<input type="hidden" name="contact_id" value="<?= $quotation->contact_history_id ?>">
					<div class="form-group">
						<label>Fixed Audit Date</label>
						<input type="text" class="form-control flatpickr-basic fixed-audit-date" name="audit_fixed_date" required>
					</div>
					<?php if ($quotation->type == 'ISO'): ?>
						<div class="form-group">
							<label>Invoice For </label>
							<?php if ($quotation->client_pay_3_years == 'Yes'): ?>
								<input type="text" class="form-control invoice-stage-audit" name="invoice_type" value="All cycle" readonly>
							<?php else: ?>
								<select class="form-control invoice-stage-audit" name="invoice_type">
									<option value="">-- select invoice type --</option>
									<?php foreach ($invoice_types as $key => $type): ?>
										<option value="<?= $type ?>"><?= $type ?></option>
									<?php endforeach; ?>
								</select>
								<!-- <?php foreach ($invoice_types as $key => $type): ?>
									<?php if (count($invoice_type_created) == $key): ?>
										<input type="text" class="form-control invoice-stage-audit" name="invoice_type" value="<?= $type ?>" readonly>
									<?php endif; ?>
								<?php endforeach; ?> -->
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ($quotation->type == 'Bizsafe'): ?>
						<input type="hidden" class="form-control" name="invoice_type" value="Bizsafe" readonly>
					<?php endif; ?>
					<?php if ($quotation->type == 'Training'): ?>
						<input type="hidden" class="form-control" name="invoice_type" value="Training" readonly>
					<?php endif; ?>
					<button type="button" class="btn btn-primary btn-create-invoice">Save & Next</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
