<div class="modal fade" id="history-modal">
	<div class="modal-dialog modal-xl sidebar-sm">
		<div class="modal-content">
			<div class="modal-header mb-1">
				<h4 class="modal-title">History</h4><br>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			</div>
			<div class="ml-2 mr-2 d-flex justify-content-between">
				<div>
				</div>
				<div>
					<select class="form-control" id="select-history">
						<option value="quotation" selected>Quotation</option>
						<option value="client">Company</option>
						<option value="address">Company Address</option>
						<option value="contact">Contact</option>
					</select>
				</div>
			</div>
			<div class="modal-body flex-grow-1">
				<div class="tab-content history-type-section quotation-history">
					<div class="quotation_iso_history">
						<div class="card template border border-dark">
							<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>#</p>
										<b class="quote_number"></b>
									</div>
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"></b>
									</div>
									<div class="col">
										<p>Quote Date</p>
										<b class="quote_date"></b>
									</div>
									<div class="col">
										<p>Quote Status</p>
										<b class="quote_status"></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Confirmation Date</p>
										<b class="confirmation_date"></b>
									</div>
									<div class="col">
										<p>Certification Cycle</p>
										<b class="cycle"></b>
									</div>
									<div class="col">
										<p>Certification Scheme</p>
										<b class="certification_scheme"></b>
									</div>
									<div class="col">
										<p>Certification Scope</p>
										<b class="scope"></b>
									</div>
									<div class="col">
										<p>Sites Covered</p>
										<b class="num_of_sites"></b>
									</div>
								</div>
								<hr>
								<div class="row row-fee-iso">
									<div class="col col-stage-audit">
										<p>Stage 1 & Stage 2 Audit</p>
										<b class="stage_audit"></b>
									</div>
									<div class="col col-surveillance-year-1">
										<p>1st Year Surveillance</p>
										<b class="surveillance_year_1"></b>
									</div>
									<div class="col col-surveillance-year-2">
										<p>2nd Year Surveillance</p>
										<b class="surveillance_year_2"></b>
									</div>
									<div class="col col-transportation">
										<p>Airfare + Local Transportation + Others</p>
										<b class="transportation"></b>
									</div>
									<div class="col">
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="quotation_bizsafe_history">
						<div class="card template-bizsafe template border border-dark">
							<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>#</p>
										<b class="quote_number"></b>
									</div>
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"></b>
									</div>
									<div class="col">
										<p>Quote Date</p>
										<b class="quote_date"></b>
									</div>
									<div class="col">
										<p>Quote Status</p>
										<b class="quote_status"></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Confirmation Date</p>
										<b class="confirmation_date"></b>
									</div>
									<div class="col">
										<p>Certification Cycle</p>
										<b class="cycle"></b>
									</div>
									<div class="col">
										<p>Certification Scheme</p>
										<b class="certification_scheme"></b>
									</div>
									<div class="col">
										<p>Certification Scope</p>
										<b class="scope"></b>
									</div>
									<div class="col">
										<p>Sites Covered</p>
										<b class="num_of_sites"></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Audit Fee</p>
										<b class="audit_fee"></b>
									</div>
									<div class="col"></div>
									<div class="col"></div>
									<div class="col"></div>
									<div class="col"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="quotation_training_history">
						<div class="card template border border-dark">
							<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>#</p>
										<b class="quote_number"></b>
									</div>
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"></b>
									</div>
									<div class="col">
										<p>Quote Date</p>
										<b class="quote_date"></b>
									</div>
									<div class="col">
										<p>Quote Status</p>
										<b class="quote_status"></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Confirmation Date</p>
										<b class="confirmation_date"></b>
									</div>
									<div class="col">
										<p>Invoice To</p>
										<b class="invoice_to"></b>
									</div>
									<div class="col">
										<p>Training Type</p>
										<b class="training_type"></b>
									</div>
									<div class="col">
										<p>Training Description</p>
										<b class="training_description"></b>
									</div>
									<div class="col">
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Total Amount</p>
										<b class="total_amount"></b>
									</div>
									<div class="col">
										<p>Discount</p>
										<b class="discount"></b>
									</div>
									<div class="col">
										<p>Payment Terms</p>
										<b class="payment_terms"></b>
									</div>
									<div class="col">
										<p>Duration</p>
										<b class="duration"></b>
									</div>
									<div class="col">
										<p>Airfare + Local Transportation + Others</p>
										<b class="transportation"></b>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="quotation-history-section"></div>
				</div>

				<div class="tab-content history-type-section client-history">
					<div class="client-template">
						<div class="card template border border-dark">
							<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"></b>
									</div>
									<div class="col">
										<p>Name</p>
										<b class="name"></b>
									</div>
									<div class="col">
										<p>UEN</p>
										<b class="uen"></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Phone</p>
										<b class="phone"></b>
									</div>
									<div class="col">
										<p>Fax</p>
										<b class="fax"></b>
									</div>
									<div class="col">
										<p>Email</p>
										<b class="email"></b>
									</div>
									<div class="col">
										<p>Website</p>
										<b class="website"></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>No of Employee</p>
										<b class="total_employee"></b>
									</div>
									<div class="col"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="client-history-section"></div>
				</div>

				<div class="tab-content history-type-section address-history">
					<div class="address-template">
						<div class="card template border border-dark">
							<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"></b>
									</div>
									<div class="col">
									</div>
									<div class="col">
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Address 1</p>
										<b class="address"></b>
									</div>
									<div class="col">
										<p>Address 2</p>
										<b class="address_2"></b>
									</div>
									<div class="col">
										<p>Postal Code</p>
										<b class="postal_code"></b>
									</div>
									<div class="col">
										<p>Country</p>
										<b class="country"></b>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="address-history-section"></div>
				</div>

				<div class="tab-content history-type-section contact-history">
					<div class="contact-template">
						<div class="card template border border-dark">
							<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"></b>
									</div>
									<div class="col">
										<p>Contact Status</p>
										<b class="contact_status"></b>
									</div>
									<div class="col"></div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Salutation</p>
										<b class="salutation"></b>
									</div>
									<div class="col">
										<p>Name</p>
										<b class="name"></b>
									</div>
									<div class="col">
										<p>Position</p>
										<b class="position"></b>
									</div>
									<div class="col">
										<p>Department</p>
										<b class="department"></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Email</p>
										<b class="email"></b>
									</div>
									<div class="col">
										<p>Phone (direct)</p>
										<b class="phone"></b>
									</div>
									<div class="col">
										<p>Fax (direct) </p>
										<b class="fax"></b>
									</div>
									<div class="col">
										<p>Mobile</p>
										<b class="mobile"></b>
									</div>
								</div>
							</div>
					</div>
					</div>
					<div class="contact-history-section"></div>
				</div>

			</div>
		</div>
	</div>
</div>
