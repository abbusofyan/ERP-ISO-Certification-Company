<div class="d-flex justify-content-between">
	<h4 class="text-middle mt-1">Address</h4>
	<button type="button" class="btn btn-primary btn-add-address">
		<i data-feather="plus" class="mr-1"></i> Add Site Address
	</button>
</div>
<div class="card mt-2 address-section">
	<div class="card-body">
		<div class="alert alert-primary alert-dismissible error-address-validation fade show" role="alert"></div>
		<div class="form-group row select-address-section">
			<label for="inputPassword" class="col-sm-2 col-form-label">Select Address</label>
			<div class="col-sm-10">
				<select class="form-control select2 select-select2 multiple" multiple="multiple" id="select-address">
					<option value="">-- Select Site --</option>
				</select>
			</div>
		</div>

		<div class="selected-address-section"></div>
		<div class="added-address-section"></div>
	</div>

</div>
