<style media="screen">
	.ui-autocomplete {
		position: absolute;
		z-index: 1000;
		cursor: default;
		padding: 0;
		margin-top: 2px;
		list-style: none;
		background-color: #ffffff;
		border: 1px solid #ccc;
		-webkit-border-radius: 5px;
		   -moz-border-radius: 5px;
				border-radius: 5px;
		-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		   -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
				box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	}
	.ui-autocomplete > li {
		padding: 3px 20px;
	}
	.ui-autocomplete > li.ui-state-focus {
		background-color: #DDD;
	}
	.ui-helper-hidden-accessible {
		display: none;
	}
</style>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header border-bottom">
				<h4 class="card-title">Quote Detail</h4>
			</div>
			<div class="card-body">
				<br>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="quote_date">Quote Date <span class="text-danger">*</span></label>
							<input type="text" class="form-control flatpickr-basic quote_date" title="Quote Date" name="training-quote_date" id="quote_date" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group ui-widget">
							<label for="referred_by">Referred By <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="training_referred_by" title="Referred By" name="training-referred_by" required>
						</div>
					</div>
					<div class="col-6 hidden">
						<div class="form-group">
							<label for="quote_cycle">Certificate Cycle </label>
							<input type="text" class="form-control" title="Certificate Cycle" id="training_certificate_cycle" name="training-certification_cycle" value="1">
							<!-- <select class="form-control select2 select-select2" title="Certificate Cycle" id="training_certificate_cycle" name="training-certification_cycle">
								<option value="">-- Select Certificate Cycle --</option>
								<?php foreach ($certification_cycle as $cycle): ?>
									<option value="<?= $cycle->id ?>"><?= $cycle->name ?></option>
								<?php endforeach; ?>
							</select> -->
						</div>
					</div>
					<div class="col-6 transfer-related-field">
						<label for="upload_application_form">Upload Application Form</label>
						<div class="form-group custom-file">
							<input type="file" class="custom-file-input" name="training-certification_and_reports_file" >
							<label class="custom-file-label" for="certification_and_reports_file">Choose file...</label>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quote_cycle">Invoice To <span class="text-danger">*</span></label>
							<select class="form-control select2 select-select2" title="Invoice To" name="training-invoice_to" required>
								<option value="">-- Select Invoice To --</option>
								<option value="Client">Client</option>
								<option value="Consultant">Consultant</option>
							</select>

						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quote_cycle">Group of Companies</label>
							<select class="form-control select2 select-select2 multiple" name="training-group_company[]" multiple="multiple" id="select-group-of-companies">
								<option value="">-- Select company --</option>
								<?php foreach ($clients as $client): ?>
									<option value="<?= $client->id ?>"><?= $client->name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-12">
						<div class="form-group">
							<label for="terms">Terms & Conditions</label>
							<textarea class="form-control ckeditor" name="training-terms_and_conditions" rows="3">
								<p>CONTRACT ACCEPTANCE TERMS &amp; CONDITIONS</p>

								<p>This Agreement is entered into basis of Quote Ref: [###]. It is further governed by the Advanced System Assurance Pte Ltd Terms &amp; Conditions, <br>
									Terms &amp; Conditions <br>
									<li>All prices are not subjected to Goods and Services Tax &ldquo;GST&rdquo;. </li>
									<li>A fee of $400 (exclusive GST) will be imposed for issue of additional or replacement of training certificate of approval issued.</li>
								</p>

								<p> Please return this proposal to:</p>

								<p>admin1@asasg.com/</p>

								<p>admin2@asasg.com/</p>

								<p>viji@asasg.com</p>
							</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
