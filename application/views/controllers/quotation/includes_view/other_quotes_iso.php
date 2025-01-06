<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-3">
				<p>#</p>
				<b><?= $quote->number ?></b>
			</div>
			<div class="col-2">
				<p>Modification Date</p>
				<b><?= human_timestamp($quote->updated_on) ?></b>
			</div>
			<div class="col-3">
				<p>Modified By</p>
				<b><?= $quote->updated_by ? $quote->updated_by->first_name . ' ' . $quote->updated_by->last_name : '' ?></b>
			</div>
			<div class="col-2">
				<p>Quote Date</p>
				<b><?= human_date($quote->quote_date) ?></b>
			</div>
			<div class="col-2">
				<p>Quote Status</p>
				<b><?= $quote->status ?></b>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-3">
				<p>No of Employee</p>
				<b><?= $quote->address->total_employee ?></b>
			</div>
			<div class="col-2">
				<p>Confirmation Date</p>
				<b><?= human_timestamp($quote->confirmed_on) ?></b>
			</div>
			<div class="col-3">
				<p>Quotation Type</p>
				<b><?= $quote->type ?></b>
			</div>
			<div class="col-2">
				<p>Certification Scheme</p>
				<?php if($quote->certification_scheme) {
						foreach ($quote->certification_scheme_arr as $key => $scheme) { ?>
							<?php if (!array_key_exists($key, $quote->accreditation_arr)): ?>
								<b>- <?= $scheme . $accreditation ?></b><br>
							<?php else: ?>
								<?php $accreditation = '('.$quote->accreditation_arr[$key].')'; ?>
								<b>- <?= $scheme . $accreditation ?></b><br>
							<?php endif; ?>
				<?php }	} ?>

			</div>
			<div class="col-2">
				<p>Certification Scope</p>
				<b><?= $quote->scope ?></b>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-3">
				<p>Sites Covered</p>
				<b><?= $quote->num_of_sites ?></b>
			</div>
			<div class="col-2">
				<p>Stage 1 & Stage 2 Audit</p>
				<b><?= money_number_format($quote->stage_audit, $quote->address->country) ?></b>
			</div>
			<div class="col-3">
				<p>1st Year Surveillance</p>
				<b><?= money_number_format($quote->surveillance_year_1, $quote->address->country) ?></b>
			</div>
			<div class="col-2">
				<p>2nd Year Surveillance</p>
				<b><?= money_number_format($quote->surveillance_year_2, $quote->address->country) ?></b>
			</div>
			<div class="col-2">
				<p>Airfare + Local Transportation + Others</p>
				<b><?= (int)$quote->transportation ? money_number_format($quote->transportation, $quote->address->country) : $quote->transportation ?></b>
			</div>
		</div>
	</div>
</div>
