<div class="tab-pane fade show" id="application-form-section" role="tabpanel">

	<div class="row">
		<div class="col-12">
			<div class="card">
				<table class="datatables-basic table datatable-application-form" width="100%" data-url="<?php echo htmlspecialchars(site_url("dt/client_application_form")); ?>" data-client-id="<?= $client->id ?>" data-csrf="<?php echo htmlspecialchars(json_encode($csrf)); ?>">
					<thead>
						<tr>
							<th data-priority="1">No</th>
							<th data-priority="3">Date Submitted</th>
							<th data-priority="2" width="100">Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
