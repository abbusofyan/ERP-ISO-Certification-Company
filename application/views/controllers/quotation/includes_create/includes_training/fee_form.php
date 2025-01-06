<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="card-title">Assessment Fee Details</h4>
			</div>
			<div class="card-body">
				<br>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="stage_audit">Total Amount <span class="text-danger">*</span></label>
							<input type="number" class="form-control" name="training-total_amount" placeholder="100" title="Total Amount" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="surveillance_year_1">Discount <span class="text-danger">*</span></label>
							<input type="number" class="form-control" name="training-discount" placeholder="100" title="Discount" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="surveillance_year_2">Payment Terms <span class="text-danger">*</span></label>
							<select class="form-control select2 select-select2 payment_terms" name="training-payment_terms" title="Payment Terms" required>
								<option value="">-- select payment terms --</option>
								<?php foreach ($payment_terms as $payment): ?>
									<option value="<?= $payment->name ?>"><?= $payment->name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="transportation">Duration <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="training-duration" title="Duration" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="transportation">Airfare + Local Transportation + Others</label>
							<input type="text" class="form-control training_assesment_fee_transportation" name="training-transportation">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="assesment_fee_attachments">Attachments</label>
					    <input type="file" class="form-control-file training-assesment_fee_attachments" id="exampleFormControlFile1" name="training-assesment_fee_attachments[]" title="Assessment Fee Attachment" multiple>
							<p class="training-selected-file"></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<div class="form-group">
							<label for="assesment_note">Add Note</label>
							<textarea class="form-control" name="training-note" rows="3" placeholder="type note here"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
