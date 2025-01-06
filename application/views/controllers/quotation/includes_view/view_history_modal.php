<div class="modal fade" id="view-history-modal">
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
					<?php if ($quotation->type == 'ISO'): ?>
						<?php foreach ($quotation_history as $key => $quote): ?>
							<div class="card border border-dark">
								<?php if ($key == 0): ?>
									<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
								<?php endif; ?>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<p>#</p>
											<b class="quote_number"><?= $quote->number ?></b>
										</div>
										<div class="col">
											<p>Modification Date</p>
											<b class="modification_date"><?= $quote->updated_on ?></b>
										</div>
										<div class="col">
											<p>Modified By</p>
											<b class="modified_by"><?= $quote->updated_by ?></b>
										</div>
										<div class="col">
											<p>Quote Date</p>
											<b class="quote_date"><?= human_date($quote->quote_date, 'd M Y') ?></b>
										</div>
										<div class="col">
											<p>Quote Status</p>
											<b class="quote_status"><?= $quote->status ?></b>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Confirmation Date</p>
											<b class="confirmation_date"><?= $quote->confirmed_on ?></b>
										</div>
										<div class="col">
											<p>Certification Cycle</p>
											<b class="cycle"><?= $quote->certification_cycle->name ?></b>
										</div>
										<div class="col">
											<p>Certification Scheme</p>
											<b class="certification_scheme">
												<?php if($quote->certification_scheme) {
														foreach ($quote->certification_scheme_arr as $key => $scheme) { ?>
															<?php if (!array_key_exists($key, $quote->accreditation_arr)): ?>
																<b>- <?= $scheme . $accreditation ?></b><br>
															<?php else: ?>
																<?php $accreditation = '('.$quote->accreditation_arr[$key].')'; ?>
																<b>- <?= $scheme . $accreditation ?></b><br>
															<?php endif; ?>
												<?php }	} ?>
											</b>
										</div>
										<div class="col">
											<p>Certification Scope</p>
											<b class="scope"><?= nl2br($quote->scope) ?></b>
										</div>
										<div class="col">
											<p>Sites Covered</p>
											<b class="num_of_sites"><?= $quote->num_of_sites ?></b>
										</div>
									</div>
									<hr>
									<div class="row">
										<?php if (in_array($quotation->certification_cycle->name, ['New', 'Re-Audit New'])) { ?>
											<div class="col">
												<p>Stage 1 & Stage 2 Audit</p>
												<b class="stage_audit"><?= money_number_format($quote->stage_audit, $quote->address->country) ?></b>
											</div>
										<?php } ?>
										<?php if ($quotation->certification_cycle->name == 'Re-Audit') { ?>
											<div class="col">
												<p>Stage 2 Audit</p>
												<b class="stage_audit"><?= money_number_format($quote->stage_audit, $quote->address->country) ?></b>
											</div>
										<?php } ?>
										<?php if(in_array($quotation->certification_cycle->name, ['New', 'Transfer 1st Year Surveillance', 'Re-Audit', 'Re-Audit New'])) { ?>
											<div class="col">
												<p>1st Year Surveillance</p>
												<b class="surveillance_year_1"><?= money_number_format($quote->surveillance_year_1, $quote->address->country) ?></b>
											</div>
										<?php }?>
										<div class="col">
											<p>2nd Year Surveillance</p>
											<b class="surveillance_year_2"><?= money_number_format($quote->surveillance_year_2, $quote->address->country) ?></b>
										</div>
										<div class="col">
											<p>Airfare + Local Transportation + Others</p>
											<b class="transportation"><?= $quote->transportation ?></b>
										</div>
										<?php if (in_array($quotation->certification_cycle->name, ['New', 'Re-Audit New', 'Re-Audit'])) { ?>
											<div class="col">
											</div>
										<?php } ?>
										<?php if(in_array($quotation->certification_cycle->name, ['Transfer 1st Year Surveillance'])) { ?>
											<div class="col">
											</div>
											<div class="col">
											</div>
										<?php }?>
										<?php if(in_array($quotation->certification_cycle->name, ['Transfer 2nd Year Surveillance'])) { ?>
											<div class="col">
											</div>
											<div class="col">
											</div>
											<div class="col">
											</div>
										<?php }?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ($quotation->type == 'Bizsafe'): ?>
						<?php foreach ($quotation_history as $key => $quote): ?>
							<div class="card">
								<?php if ($key == 0): ?>
									<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
								<?php endif; ?>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<p>#</p>
											<b><?= $quote->number ?></b>
										</div>
										<div class="col">
											<p>Modification Date</p>
											<b><?= $quote->updated_on ?></b>
										</div>
										<div class="col">
											<p>Modified By</p>
											<b><?= $quote->updated_by ?></b>
										</div>
										<div class="col">
											<p>Quote Date</p>
											<b><?= human_date($quote->quote_date) ?></b>
										</div>
										<div class="col">
											<p>Quote Status</p>
											<b><?= $quote->status ?></b>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>No of Employee</p>
											<b><?= $quote->address->total_employee ?></b>
										</div>
										<div class="col">
											<p>Confirmation Date</p>
											<b><?= ($quote->confirmed_on) ?></b>
										</div>
										<div class="col">
											<p>Certification Cycle</p>
											<b><?= $quote->certification_cycle->name ?></b>
										</div>
										<div class="col">
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
										<div class="col">
											<p>Certification Scope</p>
											<b><?= $quote->scope ?></b>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Sites Covered</p>
											<b><?= $quote->num_of_sites ?></b>
										</div>
										<div class="col">
											<p>Audit Fee</p>
											<b><?= money_number_format($quote->audit_fee, $quote->address->country) ?></b>
										</div>
										<div class="col"></div>
										<div class="col"></div>
										<div class="col"></div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ($quotation->type == 'Training'): ?>
						<?php foreach ($quotation_history as $key => $quote): ?>
							<div class="card border border-dark">
								<?php if ($key == 0): ?>
									<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
								<?php endif; ?>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<p>#</p>
											<b class="quote_number"><?= $quote->number ?></b>
										</div>
										<div class="col">
											<p>Modification Date</p>
											<b class="modification_date"><?= $quote->updated_on ?></b>
										</div>
										<div class="col">
											<p>Modified By</p>
											<b class="modified_by"><?= $quote->updated_by ?></b>
										</div>
										<div class="col">
											<p>Quote Date</p>
											<b class="quote_date"><?= $quote->quote_date ?></b>
										</div>
										<div class="col">
											<p>Quote Status</p>
											<b class="quote_status"><?= $quote->status ?></b>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Confirmation Date</p>
											<b class="confirmation_date"><?= $quote->confirmed_on ?></b>
										</div>
										<div class="col">
											<p>Invoice To</p>
											<b class="invoice_to"><?= $quote->invoice_to ?></b>
										</div>
										<div class="col">
											<p>Training Type</p>
											<?php foreach ($quote->training_type_arr as $training_type): ?>
												<b>- <?= $training_type ?></b><br>
											<?php endforeach; ?>
										</div>
										<div class="col">
											<p>Training Description</p>
											<b class="training_description"><?= nl2br($quote->training_description) ?></b>
										</div>
										<div class="col">
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col">
											<p>Total Amount</p>
											<b class="total_amount"><?= money_number_format($quote->total_amount, $quote->address->country) ?></b>
										</div>
										<div class="col">
											<p>Discount</p>
											<b class="discount"><?= money_number_format($quote->discount, $quote->address->country) ?></b>
										</div>
										<div class="col">
											<p>Payment Terms</p>
											<b class="payment_terms"><?= $quote->payment_terms ?></b>
										</div>
										<div class="col">
											<p>Duration</p>
											<b class="duration"><?= $quote->duration ?></b>
										</div>
										<div class="col">
											<p>Airfare + Local Transportation + Others</p>
											<b class="transportation"><?= $quote->transportation ?></b>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<div class="tab-content history-type-section client-history">
					<?php foreach ($client_history as $key => $client): ?>
						<div class="card template border border-dark">
							<?php if ($key == 0): ?>
								<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<?php endif; ?>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"><?= $client->updated_on ?></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"><?= $client->updated_by ?></b>
									</div>
									<div class="col">
										<p>Name</p>
										<b class="name"><?= $client->name ?></b>
									</div>
									<div class="col">
										<p>UEN</p>
										<b class="uen"><?= $client->uen ?></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Phone</p>
										<b class="phone"><?= $client->phone ?></b>
									</div>
									<div class="col">
										<p>Fax</p>
										<b class="fax"><?= $client->fax ?></b>
									</div>
									<div class="col">
										<p>Email</p>
										<b class="email"><?= $client->email ?></b>
									</div>
									<div class="col">
										<p>Website</p>
										<b class="website"><?= $client->website ?></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>No of Employee</p>
										<b class="total_employee"><?= $client->total_employee ?></b>
									</div>
									<div class="col"></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="tab-content history-type-section address-history">
					<?php foreach ($address_history as $key => $address): ?>
						<div class="card template border border-dark">
							<?php if ($key == 0): ?>
								<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<?php endif; ?>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"><?= $address->updated_on ?></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"><?= $address->updated_by ?></b>
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
										<b class="address"><?= $address->address ?></b>
									</div>
									<div class="col">
										<p>Address 2</p>
										<b class="address_2"><?= $address->address_2 ?></b>
									</div>
									<div class="col">
										<p>Postal Code</p>
										<b class="postal_code"><?= $address->postal_code ?></b>
									</div>
									<div class="col">
										<p>Country</p>
										<b class="country"><?= $address->country ?></b>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="tab-content history-type-section contact-history">
					<?php foreach ($contact_history as $key => $contact): ?>
						<div class="card template border border-dark">
							<?php if ($key == 0): ?>
								<p class="text-center mt-2 bg-dark text-white current-data-label">Current Data</p>
							<?php endif; ?>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>Modification Date</p>
										<b class="modification_date"><?= $contact->updated_on ?></b>
									</div>
									<div class="col">
										<p>Modified By</p>
										<b class="modified_by"><?= $contact->updated_by ?></b>
									</div>
									<div class="col">
										<p>Contact Status</p>
										<b class="contact_status"><?= $contact->status ?></b>
									</div>
									<div class="col"></div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Salutation</p>
										<b class="salutation"><?= $contact->salutation ?></b>
									</div>
									<div class="col">
										<p>Name</p>
										<b class="name"><?= $contact->name ?></b>
									</div>
									<div class="col">
										<p>Position</p>
										<b class="position"><?= $contact->position ?></b>
									</div>
									<div class="col">
										<p>Department</p>
										<b class="department"><?= $contact->department ?></b>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col">
										<p>Email</p>
										<b class="email"><?= $contact->email ?></b>
									</div>
									<div class="col">
										<p>Phone (direct)</p>
										<b class="phone"><?= $contact->phone ?></b>
									</div>
									<div class="col">
										<p>Fax (direct) </p>
										<b class="fax"><?= $contact->fax ?></b>
									</div>
									<div class="col">
										<p>Mobile</p>
										<b class="mobile"><?= $contact->mobile ?></b>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</div>
</div>
