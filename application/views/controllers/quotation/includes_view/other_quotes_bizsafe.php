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
				<p>Invoice To</p>
				<b><?= $quotation->invoice_to ?></b>
			</div>
			<div class="col-2">
				<p>Audit Fees</p>
				<b><?= money_number_format($quote->audit_fee, $quote->address->country) ?></b>
			</div>
		</div>
	</div>
</div>
