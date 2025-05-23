<div class="modal fade" id="generate-receipt-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Generate Receipt</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		<?php echo form_open_multipart(site_url('invoice/generate-receipt/'.$quotation->id), ['autocomplete' => 'off', 'id' => 'form-generate-receipt']); ?>
			<div class="row mb-1">
				<div class="col">
					<label>Select Invoice(s)</label>
					<?php foreach (array_filter($invoices) as $key => $invoice): ?>
						<?php if ($invoice->status != 'Paid'): ?>
							<div class="form-group">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input invoice-amount" id="invoice-amount-<?= $key ?>" name="invoice_id[]" data-invoice-amount="<?= $invoice->amount - $invoice->paid ?>" value="<?= $invoice->id ?>" checked/>
									<label class="custom-control-label" for="invoice-amount-<?= $key ?>">
										<?= $invoice->number ?> | <?= money_number_format(($invoice->amount - $invoice->paid), 0) ?> | <?= invoice_status_badge($invoice->status) ?>
									</label>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="col">
					<div class="form-group">
						<label>Payment Method <span class="text-danger">*</span></label>
						<select class="form-control select-payment-method" name="payment_method">
							<option value="">-- Select Payment Method --</option>
							<option value="Bank Transfer">Bank Transfer</option>
							<option value="Cash">Cash</option>
							<option value="Cheque">Cheque</option>
							<option value="NETs">NETs</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row mb-1">
				<div class="col">
					<div class="form-group">
						<div class="d-flex justify-content-between">
							<label>Total Selected Amount</label>
						</div>
						<b id="total-selected-amount"></b>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<div class="d-flex justify-content-between">
							<label>Paid Amount <span class="text-danger">*</span></label>
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="pay-total-selected-amount">
								<label class="form-check-label" for="pay-total-selected-amount">Pay total selected amount</label>
							</div>
						</div>
						<input type="number" id="amount-to-pay" class="form-control" name="paid_amount" value="">
					</div>
				</div>
			</div>
			<div class="row mb-1 additional-payment-fields field-bank-transfer">
				<div class="col">
					<div class="form-group">
						<label>Transfer Date <span class="text-danger">*</span></label>
						<input type="text" class="form-control flatpickr-basic" title="Transfer Date" name="transfer_date" value="" required>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label>Discount Amount ($)</label>
						<input type="number" class="form-control" name="discount[]" value="">
					</div>
				</div>
			</div>
			<div class="row mb-1 additional-payment-fields field-net-cash">
				<div class="col">
					<div class="form-group">
						<label>Received Date <span class="text-danger">*</span></label>
						<input type="text" class="form-control flatpickr-basic" title="Received Date" name="received_date" value="" required>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label>Discount Amount ($)</label>
						<input type="number" class="form-control" name="discount[]" value="">
					</div>
				</div>
			</div>
			<div class="row mb-1 additional-payment-fields field-cheque">
				<div class="col-6">
					<div class="form-group">
						<label>Cheque Received Date <span class="text-danger">*</span></label>
						<input type="text" class="form-control flatpickr-basic" title="Cheque Received Date" name="cheque_received_date" value="" required>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>Discount Amount ($)</label>
						<input type="number" class="form-control" name="discount[]" value="">
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>Cheque No <span class="text-danger">*</span></label>
						<input type="number" class="form-control" title="Cheque No" name="cheque_no" value="" required>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>Cheque Date <span class="text-danger">*</span></label>
						<input type="text" class="form-control flatpickr-basic" title="Cheque Date" name="cheque_date" value="" required>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>Bank <span class="text-danger">*</span></label>
						<input type="text" class="form-control" title="Bank" name="bank" value="" required>
					</div>
				</div>
			</div>
			<label>Note</label>
			<textarea name="note" class="form-control generate-receipt-note" rows="4"></textarea>
			<button type="button" class="btn btn-primary float-right mt-1 btn-submit-receipt">Save</button>
		<?php echo form_close(); ?>
	  </div>
	</div>
  </div>
</div>
