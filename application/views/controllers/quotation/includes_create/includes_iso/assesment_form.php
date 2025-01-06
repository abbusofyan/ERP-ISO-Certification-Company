<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="card-title">Assesment Fee Details</h4>
			</div>
			<div class="card-body">
				<br>
				<div class="row">
					<div class="col-6 assesment_fee" id="iso-stage_audit">
						<div class="form-group">
							<label for="stage_audit" id="stage-audit-label">Stage 1 & Stage 2 Audit</label>
							<input type="number" class="form-control" name="iso-stage_audit" title="Stage 1 & Stage 2 Audit" required>
						</div>
					</div>
					<div class="col-6 assesment_fee" id="iso-surveillance_year_1">
						<div class="form-group">
							<label for="surveillance_year_1">1st Year Surveillance</label>
							<input type="number" class="form-control" name="iso-surveillance_year_1" title="1st Year Surveillance" required>
						</div>
					</div>
					<div class="col-6 assesment_fee" id="iso-surveillance_year_2">
						<div class="form-group">
							<label for="surveillance_year_2">2nd Year Surveillance</label>
							<input type="number" class="form-control" name="iso-surveillance_year_2" title="2nd Year Surveillance" required>
						</div>
					</div>
					<div class="col-6 assesment_fee" id="iso-transportation">
						<div class="form-group">
							<label for="transportation">Airfare + Local Transportation + Others</label>
							<input type="text" class="form-control assesment_fee_transportation iso_assesment_fee_transportation" name="iso-transportation" title="Airfare + Transportation" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="assesment_fee_attachments">Attachments</label>
					    <input type="file" class="form-control-file iso-assesment_fee_attachments" id="exampleFormControlFile1" name="iso-assesment_fee_attachments[]" title="Assessment Fee Attachment" multiple>
							<p class="iso-selected-file"></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<div class="form-group">
							<label for="assesment_note">Add Note</label>
							<textarea class="form-control" name="iso-note" rows="3"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
