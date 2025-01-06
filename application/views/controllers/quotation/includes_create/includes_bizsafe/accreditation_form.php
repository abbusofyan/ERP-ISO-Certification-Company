<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="card-title">Accreditation</h4>
			</div>
			<div class="card-body">
				<br>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="referred_by">Certification Scope <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="bizsafe-scope" title="Certification Scope" required>
						</div>
					</div>
					<div class="col-2">
						<div class="form-group">
							<label for="referred_by">Number of Sites <span class="text-danger">*</span></label>
							<input type="number" class="form-control" id="number-of-sites" value="1" name="bizsafe-num_of_sites" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="quote_cycle">Certification Scheme <span class="text-danger">*</span></label>
							<select class="form-control select2 select-select2 certification-scheme-bizsafe" title="Certification Scheme" name="bizsafe-certification_scheme[]" required>
								<option value="">-- Select Certification Scheme --</option>
								<?php foreach ($certification_schemes as $scheme): ?>
									<?php if(strtolower(explode(' ', $scheme->name)[0]) == 'bizsafe') { ?>
										<option value="<?= $scheme->id ?>"><?= $scheme->name ?></option>
									<?php } ?>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label for="quote_cycle">Accreditation</label>
							<select class="form-control select2 select-select2 accreditation-bizsafe" title="Accreditation" name="bizsafe-accreditation[]">
								<option value="0">-- Select Accreditation --</option>
								<?php foreach ($accreditations as $acc): ?>
									<option value="<?= $acc->id ?>"><?= $acc->name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="accreditation-bizsafe-template hidden">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="quote_cycle">Certification Scheme <span class="text-danger">*</span></label>
								<select class="form-control certification-scheme-bizsafe" title="Certification Scheme" name="bizsafe-certification_scheme[]">
									<option value="0">-- Select Certification Scheme --</option>
									<?php foreach ($certification_schemes as $scheme): ?>
										<?php if(explode(' ', $scheme->name)[0] == 'Bizsafe') { ?>
											<option value="<?= $scheme->id ?>"><?= $scheme->name ?></option>
										<?php } ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label for="quote_cycle">Accreditation</label>
								<select class="form-control accreditation-bizsafe" title="Accreditation" name="bizsafe-accreditation[]" >
									<option value="0">-- Select Accreditation --</option>
									<?php foreach ($accreditations as $acc): ?>
										<option value="<?= $acc->id ?>"><?= $acc->name ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-1 my-auto">
							<button type="button" class="btn btn-primary btn-sm my-auto btn-delete-accreditation-bizsafe" name="button">
								<i data-feather="trash"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="new-accreditation-bizsafe-section mb-1"></div>
				<button type="button" class="btn btn-block btn-white border-primary text-primary btn-add-accreditation-bizsafe">Add More Standards</button>
			</div>
		</div>
	</div>
</div>
