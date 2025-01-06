<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="card-title">Training</h4>
			</div>
			<div class="card-body">
				<br>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="stage_audit">Training Type <span class="text-danger">*</span></label>
							<select class="form-control select2 select-select2" title="Training Type" name="training-training_type[]" multiple required>
								<option value="">-- select training type --</option>
								<?php foreach ($training_type as $type): ?>
									<?php if (!$quotation->training_type): ?>
										<option value="<?= $type->id ?>"><?= $type->name ?></option>
									<?php else: ?>
										<option value="<?= $type->id ?>" <?= in_array($type->id, explode(',', $quotation->training_type)) ? 'selected' : '' ?>><?= $type->name ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="stage_audit">Training Description <span class="text-danger">*</span></label>
							<textarea name="training-training_description" rows="3" id="training_description" placeholder="training description" class="form-control" title="Training Description" required><?= $quotation->training_description ?>
							</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
