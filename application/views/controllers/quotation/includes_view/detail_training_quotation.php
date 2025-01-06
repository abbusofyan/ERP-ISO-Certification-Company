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
					<div class="col-3"></div>
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
				<b><?= $quotation->terms_and_conditions ?></b>
			</div>
		</div>
	</div>
</div>
<hr class="m-0"><br>

<h4>Training</h4>
<div class="row mt-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-4">
						<p>Training Type</p>
						<?php foreach ($quotation->training_type_arr as $type): ?>
							<b>- <?= $type ?></b><br>
						<?php endforeach; ?>
					</div>
				</div>
				<hr>
				<p>Training Description</p>
				<b><?= nl2br($quotation->training_description) ?></b>
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
					<div class="col-md-3 col-sm-12">
						<p>Total Amount</p>
						<b><?= money_number_format($quotation->total_amount, $quotation->address->country) ?></b>
					</div>
					<div class="col-md-3 col-sm-12">
						<p>Discount</p>
						<b><?= money_number_format($quotation->discount, $quotation->address->country) ?></b>
					</div>
					<div class="col-md-4 col-sm-12">
						<p>Airfare + Local Transportation + Others</p>
						<b><?= $quotation->transportation ?></b>
					</div>
					<div class="col-md-2 col-sm-12">
						<p>Attachments</p>
						<?php if ($quotation->assessment_fee_file): ?>
							<b><a data-toggle="modal" class="text-danger" data-target="#view-attachment-modal">
							  View Attachment
							</a></b>
						<?php endif; ?>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<p>Payment Terms</p>
						<b><?= $quotation->payment_terms ?></b>
					</div>
					<div class="col-md-6 col-sm-12">
						<p>Duration</p>
						<b><?= $quotation->duration ?></b>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<hr class="m-0"><br>
