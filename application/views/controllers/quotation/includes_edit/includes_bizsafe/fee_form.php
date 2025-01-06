<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="card-title">Assesment Fee Detail</h4>
			</div>
			<div class="card-body">
				<br>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="stage_audit">RM Audit Fees <span class="text-danger">*</span></label>
							<input type="number" class="form-control" title="Audit Fee" name="bizsafe-audit_fee" value="<?= $quotation->audit_fee ?>" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="assesment_fee_attachments">Attachments</label>
					    <input type="file" class="form-control-file bizsafe-assesment_fee_attachments" id="exampleFormControlFile1" name="bizsafe-assesment_fee_attachments[]" title="Assessment Fee Attachment" multiple>
							<p class="bizsafe-selected-file"></p>
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
							<textarea class="form-control" name="bizsafe-note" rows="3" placeholder="type note here"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
