<h4>Quote Detail</h4>
<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-3">
						<p>Quote Date</p>
						<b><?= $quotation->quote_date ?></b>
					</div>
					<div class="col-3">
						<p>Referred By</p>
						<b><?= $quotation->referred_by ?></b>
					</div>
					<div class="col-3">
						<p>Quote Type (cycle)</p>
						<b><?= $quotation->certification_cycle->name ?></b>
					</div>
					<div class="col-3">
						<p>Upload Application Form</p>
						<b><?= $quotation->application_form ?></b>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-6">
						<p>Invoice To</p>
						<b><?= $quotation->invoice_to ?></b>
					</div>
					<div class="col-6">
						<p>Group of Companies</p>
						<?php if ($quotation->group_company): ?>
							<?php foreach ($quotation->group_company_name as $client_name): ?>
								<p> <b><?= $client_name ?></b> </p>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
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

<h4>Assesment Fee Detail</h4>
<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-6">
						<p>RM Audit Fee</p>
						<b><?= money_number_format($quotation->audit_fee , $quotation->address->country) ?></b>
					</div>

					<div class="col-6">
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
