<h4>Quote Detail</h4>
<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col">
						<p>Quote Date</p>
						<b><?= $quotation->quote_date ?></b>
					</div>
					<div class="col">
						<input type="hidden" id="quotation-id" value="<?= $quotation->id ?>">
						<input type="hidden" id="quotation-type" value="<?= $quotation->type ?>">
						<?php if ($quotation->status == 'Confirmed'): ?>
							<p>Confirm Date
								<?php if($current_user['group_id'] == 1) { ?>
									<?php if($quotation->confirmed_on) { ?>
										<button class="btn btn-white btn-edit-confirm-date text-primary py-0"><b><i data-feather="edit-3" class="ml-1"></i> Edit</b></button>
									<?php } ?>
									<button class="btn btn-white btn-save-confirm-date text-primary py-0"><b><i data-feather="check" class="ml-1"></i> Save</b></button>
								<?php } ?>
							</p>
							<input type="text" class="form-control flatpickr-basic" id="field-edit-confirm-date" value="<?= $quotation->confirmed_on ? human_timestamp($quotation->confirmed_on, 'Y-m-d') : '' ?>">
							<b id="confirm-date"><?= $quotation->confirmed_on ? human_timestamp($quotation->confirmed_on, 'Y-m-d') : ''; ?></b>
						<?php else: ?>
							<p>Confirm Date</p>
						<?php endif; ?>
					</div>
					<div class="col">
						<p>Referred By</p>
						<b><?= $quotation->referred_by ?></b>
					</div>
					<div class="col">
						<p>Certificate Cycle</p>
						<b><?= $quotation->certification_cycle->name ?></b>
					</div>
					<div class="col">
						<p>Upload Application Form</p>
						<b>#<?= $quotation->application_form ?></b>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col">
						<p>Invoice To</p>
						<b><?= $quotation->invoice_to ?></b>
					</div>
					<div class="col">
						<p>Group of Companies</p>
						<?php if ($quotation->group_company): ?>
							<?php foreach ($quotation->group_company_name as $client_name): ?>
								<p> <b><?= $client_name ?></b> </p>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<div class="col">
						<p>Client paying invoice for 3 years ?</p>
						<b><?= $quotation->client_pay_3_years ?></b>
					</div>
					<div class="col">
						<p>Consultant paying invoice for 3 years ?</p>
						<b><?= $quotation->consultant_pay_3_years ?></b>
					</div>
					<div class="col"></div>
				</div>
				<?php if ($quotation->certification_cycle->id == '2' || $quotation->certification_cycle->id == '3') { ?>
					<hr>
					<div class="row">
						<div class="col">
							<p>Previous Certificate Issue Date</p>
							<b><?= $quotation->prev_cert_issue_date ?></b>
						</div>
						<div class="col">
							<p>Previous Certificate Exp Date</p>
							<b><?= $quotation->prev_cert_exp_date ?></b>
						</div>
						<div class="col">
							<p>Previous Certification Scheme</p>
							<b><?= $quotation->prev_certification_scheme ?></b>
						</div>
						<div class="col">
							<p>Previous Accreditation</p>
							<b><?= $quotation->prev_accreditation ?></b>
						</div>
						<div class="col">
							<p>Previous Certification Body (CB)</p>
							<b><?= $quotation->prev_certification_body ?></b>
						</div>
					</div>
				<?php } ?>
				<hr>
				<p>Past Certification Reports</p>
				<?php if ($quotation->past_certification_report): ?>
					<b><a data-toggle="modal" class="text-danger" data-target="#view-past-certificate-attachment-modal">
					  View Attachment
					</a></b>
				<?php endif; ?>
				<hr>
				<p>Terms and conditions</p>
				<?php
					$terms = $quotation->terms_and_conditions;
					$new_terms = str_replace("[###]", $quotation->number, $terms);
					echo '<b>'.$new_terms.'</b>';
				 ?>
			</div>
		</div>
	</div>
</div>
<hr class="m-0"><br>


<h4>Accreditation</h4>
<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<p>Certification Scheme</p>
						<?php foreach ($quotation->certification_scheme_arr as $scheme): ?>
							<b>- <?= $scheme ?></b><br>
						<?php endforeach; ?>
					</div>
					<div class="col-3">
						<p>Accreditation</p>
						<?php foreach ($quotation->accreditation_arr as $accreditation): ?>
							<b>- <?= $accreditation ?></b><br>
						<?php endforeach; ?>
					</div>
					<div class="col-3">
						<p>Certification Scope</p>
						<b><?= nl2br($quotation->scope) ?></b>
					</div>
					<div class="col-3">
						<p>No of Sites</p>
						<b><?= $quotation->num_of_sites ?></b>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr class="m-0"><br>

<?php if ($quotation->num_of_sites > 1) { ?>
	<h4>Sites</h4>
	<div class="row mt-2">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<?php foreach ($quotation_address as $key => $address): ?>
						<div class="row">
							<div class="col-3">
								<p>Name</p>
								<b><?= $address->address->name ?></b>
							</div>
							<div class="col-2">
								<p>Phone</p>
								<b><?= $address->address->phone ?></b>
							</div>
							<div class="col-2">
								<p>Fax</p>
								<b><?= $address->address->fax ?></b>
							</div>
							<div class="col-3">
								<p>Address</p>
								<b><?= full_address($address->address) ?></b>
							</div>
							<div class="col-2">
								<p>No of Employee</p>
								<b><?= $address->address->total_employee ?></b>
							</div>
						</div>
						<?php if ($key+1 != count($quotation_address)) { ?>
							<hr>
						<?php } ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<hr class="m-0"><br>
<?php }?>

<h4>Assesment Fee Detail</h4>
<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<?php if (in_array($quotation->certification_cycle->name, ['New', 'Re-Audit New'])) { ?>
						<div class="col-3">
							<p>Stage 1 & Stage 2 Audit</p>
							<b><?= money_number_format($quotation->stage_audit ? $quotation->stage_audit : 0 , $quotation->address->country) ?></b>
						</div>
					<?php } ?>
					<?php if ($quotation->certification_cycle->name == 'Re-Audit') { ?>
						<div class="col-3">
							<p>Stage 2 Audit</p>
							<b><?= money_number_format($quotation->stage_audit ? $quotation->stage_audit : 0 , $quotation->address->country) ?></b>
						</div>
					<?php } ?>
					<?php if(in_array($quotation->certification_cycle->name, ['New', 'Transfer 1st Year Surveillance', 'Re-Audit', 'Re-Audit New'])) { ?>
						<div class="col-2">
							<p>1st Year Surveillance</p>
							<b><?= money_number_format($quotation->surveillance_year_1 ? $quotation->surveillance_year_1 : 0 , $quotation->address->country) ?></b>
						</div>
					<?php }?>
					<div class="col-2">
						<p>2nd Year Surveillance</p>
						<b><?= money_number_format($quotation->surveillance_year_2 ? $quotation->surveillance_year_2 : 0 , $quotation->address->country) ?></b>
					</div>
					<div class="col-3">
						<p>Airfare + Local Transportation + Others</p>
						<b><?= (int)$quotation->transportation ? money_number_format($quotation->transportation , $quotation->address->country) : $quotation->transportation ?></b>
					</div>
					<div class="col-2">
						<p>Attachments</p>
						<?php if ($quotation->assessment_fee_file): ?>
							<b><a data-toggle="modal" class="text-danger" data-target="#view-attachment-modal">
							  View Attachment
							</a></b>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr class="m-0"><br>
