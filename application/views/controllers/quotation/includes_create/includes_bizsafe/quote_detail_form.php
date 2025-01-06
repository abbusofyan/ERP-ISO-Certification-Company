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
							<input type="text" class="form-control flatpickr-basic quote_date" title="Quote Date" name="bizsafe-quote_date" id="quote_date" required>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group ui-widget">
							<label for="referred_by">Referred By <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="bizsafe_referred_by" title="Referred By" name="bizsafe-referred_by" required>
						</div>
					</div>
					<div class="col-6 hidden">
						<div class="form-group">
							<label for="quote_cycle">Certification Cycle</label>
							<input type="text" class="form-control" title="Certificate Cycle" id="bizsafe_certificate_cycle" name="bizsafe-certification_cycle" value="1">
							<!-- <select class="form-control select2 select-select2" title="Quote Type (Cycle)" id="bizsafe_certificate_cycle" name="bizsafe-certification_cycle" required>
								<option value="">-- Select Certificate Cycle --</option>
								<option value="<?= constant('DEFAULT_QUOTE_CYCLE') ?>">New</option>
							</select> -->
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quote_cycle">Invoice To <span class="text-danger">*</span></label>
							<select class="form-control select2 select-select2" title="Invoice To" name="bizsafe-invoice_to" required>
								<option value="">-- Select Invoice To --</option>
								<option value="Client">Client</option>
								<option value="Consultant">Consultant</option>
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="quote_cycle">Group of Companies</label>
							<select class="form-control select2 select-select2 multiple" name="bizsafe-group_company[]" multiple="multiple" id="select-group-of-companies">
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
							<textarea class="form-control ckeditor" name="bizsafe-terms_and_conditions" rows="3">
								<p>CONTRACT ACCEPTANCE TERMS &amp; CONDITIONS</p>

								<p>This Agreement is entered into basis of Quote Ref: [###].It is further governed by the Advanced System Assurance Pte Ltd Terms &amp; Conditions ASA-SYS-02 Rev 04, which can be viewed/downloaded in the website www.asasg.com,all of which documents the client, (as names above), by signing proposal acceptance section, accepts, agrees and acknowledges that they received, read carefully and understood. The signature of the person in charge is acceptance of all data this form. Any data found to be false may invalidate the certification.</p>

								<p>Client is subjected to external accreditation bodies assessors verification, where required</p>

								<p>Any postponement / cancellation of scheduled assessment with in 7 working days of the schedule assessment dates with be subjected to a postponement / cancellation charge of $300 (exclude GST).</p>

								<p>A fee of $500 (exclude GST) will be charged for issue of additional reprinting or replacement of original certificate of approval issued. Advanced System Assurance Pte Ltd reserves the right to accept or reject assessment application /proposal.</p>

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
