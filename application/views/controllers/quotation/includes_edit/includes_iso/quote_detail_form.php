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
							<input type="text" class="form-control flatpickr-basic quote_date" title="Quote Date" name="iso-quote_date" id="quote_date" value="<?= $quotation->quote_date ?>" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group ui-widget">
							<label for="referred_by">Referred By <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="iso_referred_by" title="Referred By" name="iso-referred_by" value="<?= $quotation->referred_by ?>" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quote_cycle">Certificate Cycle <span class="text-danger">*</span></label>
							<select class="form-control select2 select-select2" title="Certification Cycle" id="iso_certificate_cycle" name="iso-certification_cycle" required>
								<option value="">-- Select Certificate Cycle --</option>
								<?php foreach ($certification_cycle as $cycle): ?>
									<option value="<?= $cycle->id ?>" <?= $cycle->id == $quotation->certification_cycle->id ? 'selected' : '' ?>><?= $cycle->name ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-6 transfer-related-field">
						<div class="form-group">
							<label for="quote_cycle">Received Previous Reports and Certificates</label>
							<select class="form-control select2 select-select2" id="received_prev_reports" name="iso-received_prev_reports">
								<option value="">-- Choose --</option>
								<option value="Yes" <?= $quotation->received_prev_reports == 'Yes' ? 'selected' : '' ?>>Yes</option>
								<option value="No" <?= $quotation->received_prev_reports == 'No' ? 'selected' : '' ?>>No</option>
							</select>
						</div>
					</div>
					<div class="col-6 transfer-related-field">
						<div class="form-group">
							<label for="prev_cert_issue_date">Previous Cert Issue Date</label>
							<input type="text" class="form-control flatpickr-basic" id="prev-cert-issue-date" name="iso-prev_cert_issue_date" value="<?= $quotation->prev_cert_issue_date ?>">
						</div>
					</div>
					<div class="col-6  transfer-related-field">
						<div class="form-group">
							<label for="prev_cert_exp_date">Previous Cert Exp Date</label>
							<input type="text" class="form-control flatpickr-basic" id="prev-cert-exp-date" name="iso-prev_cert_exp_date" value="<?= $quotation->prev_cert_exp_date?>">
						</div>
					</div>
					<div class="col-6  transfer-related-field">
						<div class="form-group">
							<label for="prev_standard">Previous Standard (Certification Scheme)</label>
							<input type="text" class="form-control" id="prev-standard" name="iso-prev_certification_scheme" value="<?= $quotation->prev_certification_scheme ?>">
						</div>
					</div>
					<div class="col-6  transfer-related-field">
						<div class="form-group">
							<label for="prev_accreditation">Previous Accreditation</label>
							<input type="text" class="form-control" id="prev-accreditation" name="iso-prev_accreditation" value="<?= $quotation->prev_accreditation ?>">
						</div>
					</div>
					<div class="col-6  transfer-related-field">
						<div class="form-group">
							<label for="prev_cb">Previous Certification Body (CB)</label>
							<input type="text" class="form-control" id="prev-cb" name="iso-prev_certification_body" value="<?= $quotation->prev_certification_body ?>">
						</div>
					</div>
					<div class="col-6  transfer-related-field">
						<div class="form-group">
							<label for="upload_application_form">Upload 1 Certification & 2 Reports</label>
					    <input type="file" class="form-control-file iso-certification_and_reports_file" id="exampleFormControlFile1" name="iso-certification_and_reports_file[]" title="Assessment Fee Attachment" multiple>
							<p class="iso-certification_and_reports_selected_file"></p>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quote_cycle">Invoice To <span class="text-danger">*</span></label>
							<select class="form-control select2 select-select2 select-invoice-to" title="Invoice To" name="iso-invoice_to" required>
								<option value="">-- Select Invoice To --</option>
								<option value="Client" <?= $quotation->invoice_to == 'Client' ? 'selected' : '' ?>>Client</option>
								<option value="Consultant" <?= $quotation->invoice_to == 'Consultant' ? 'selected' : '' ?>>Consultant</option>
							</select>

						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quote_cycle">Group of Companies</label>
							<select class="form-control select2 select-select2 multiple" multiple="multiple" name="iso-group_company[]" id="select-group-of-companies">
								<option value="">-- Select company --</option>
								<?php foreach ($clients as $client): ?>
									<?php if ($quotation->group_company == ""): ?>
										<option value="<?= $client->id ?>"><?= $client->name ?></option>
									<?php else: ?>
										<option value="<?= $client->id ?>" <?= in_array($client->id, json_decode($quotation->group_company)) ? 'selected' : '' ?>><?= $client->name ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-6 consultant_pay_3_years_field">
						<label for="quote_cycle">Consultant paying invoice for 3 years</label><br>
						<div class="form-control-plaintext">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="iso-consultant_pay_3_years" id="consultant_pay_three_years_1" value="Yes" <?= $quotation->consultant_pay_3_years == 'Yes' ? 'checked' : '' ?>>
								<label class="form-check-label" for="consultant_pay_three_years_1">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="iso-consultant_pay_3_years" id="consultant_pay_three_years_0" value="No" <?= $quotation->consultant_pay_3_years == 'No' ? 'checked' : '' ?>>
								<label class="form-check-label" for="consultant_pay_three_years_0">No</label>
							</div>
						</div>
					</div>
					<div class="col-6 client_pay_3_years_field">
						<label for="quote_cycle">Client paying invoice for 3 years</label><br>
						<div class="form-control-plaintext">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="iso-client_pay_3_years" id="invoice_pay_three_years_1" value="Yes" <?= $quotation->client_pay_3_years == 'Yes' ? 'checked' : '' ?>>
								<label class="form-check-label" for="invoice_pay_three_years_1">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="iso-client_pay_3_years" id="invoice_pay_three_years_0" value="No" <?= $quotation->client_pay_3_years == 'No' ? 'checked' : '' ?>>
								<label class="form-check-label" for="invoice_pay_three_years_0">No</label>
							</div>
						</div>
					</div>
					<div class="col-6">
						<label for="application_form">Application Form</label>
						<div class="input-group ui-widget mb-2">
							<select class="form-control select2" name="iso-application_form">
								<option value="">-- select applicaiton form --</option>
								<?php foreach ($application_form as $form): ?>
									<option value="<?= $form->number ?>" <?= $form->number == $quotation->application_form ? 'selected' : '' ?>>#<?= $form->number ?> (<?= $form->client_name ?>)</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-12">
						<div class="form-group">
							<label for="terms">Terms & Conditions</label>
                            <textarea class="form-control ckeditor" name="iso-terms_and_conditions" rows="3">
								<p>CONTRACT ACCEPTANCE TERMS &amp; CONDITIONS</p>

								<p>This Agreement is entered into basis of Quote Ref: [###]. It is further governed by the Advanced System Assurance Pte Ltd Terms &amp; Conditions ASA-SYS-02 Rev 06, which can be viewed/downloaded in the website www.asasg.com,all of which documents the client, (as names above), by signing proposal acceptance section, accepts, agrees and acknowledges that they received, read carefully and understood. The signature of the person in charge is acceptance of all data this form. Any data found to be false may invalidate the certification.</p>

								<p>Client is subjected to external accreditation bodies assessors&rsquo; verification, where required</p>

								<p>Any postponement / cancellation of scheduled assessment with in 7 working days of the schedule assessment dates with be subjected to a postponement / cancellation charge of $300 (exclude GST).</p>

								<p>A fee of $500 (exclude GST) will be charged for issue of additional reprinting or replacement of original certificate of approval issued. Advanced System Assurance Pte Ltd reserves the right to accept or reject assessment application /proposal.</p>

								<p>Please return this proposal to:</p>

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
