<?php
	$quotation = $this->session->flashdata('quotation');
?>
<html>
    <head>
        <style>
            /** Define the margins of your page **/
						@page {
                margin-top: 220px;
                margin-bottom: 100px;
								margin-right: 30px;
								margin-left:  30px;
            }

            html, body { font-family: "Roboto", Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.4; font-weight: 400; color: #000000;}

			.row {
				display: table;
				width: 100%;
				table-layout: fixed;
				padding: 0;
				margin:0;
			}
			.col-4,
			.col-6 {
				display: table-cell;
				padding: 10px;
				padding: 0;
				margin: 0;
			}
			.col-3 {
				display: table-cell;
				width: 25%;
				padding: 0;
				margin: 0;
			}
			.col-5 {
				display: table-cell;
				width: 41.6667%;
				padding: 0;
				margin: 0;
			}
			.col-4 {
				width: 33.3333%;
				padding: 0;
				margin: 0;
			}
			.col-6 {
				width: 50%;
			}
            header {
                position: fixed;
                top: -200px;
                left: 0px;
                right: 0px;
            }

            footer {
                position: fixed;
                bottom: -100px;
                left: 0px;
                right: 0px;
                height: 50px;

                text-align: center;
                line-height: 35px;
            }

			.table { border-width: 100%; border: solid 1px; text-align: left; border-spacing: 0 5px; border-collapse: separate }
			.table-collapse { border-spacing: 0; border-collapse: collapse }

			.table-pd0 th, .table-pd0 td { padding: 0 }

			.table-items thead th, .table-items tfoot th { background-color: transparent }
			.table-items thead th { padding: 0 }
			.table-items tbody tr:nth-child(odd) td { background-color: #F3F3F4 }
			.table-items tbody tr:nth-child(even) td { background-color: #fff }

			.table-items tfoot th.bg-primary { background-color: #ff7f00; color: #ffffff }

			.table th { font-weight: bold; vertical-align: baseline }
			.table .center { text-align: center }
			.table .right { text-align: right }
			.table .top { vertical-align: top }
			.table .middle { vertical-align: middle }
			.table {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            .table td, .table th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            .table tr:hover {background-color: #ddd;}

            .table th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                color: white;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
			<div class="letter-header" style="font-size:12px">
				<div class="row" style="padding:0; margin:0;">
					<div class="col-6">
						<img class="image" src="<?= base_url('assets/img/logo-clean.png') ?>" style="width:130px; padding:0; margin:0;">
					</div>
					<div class="col-6">
						<p class="text" style="text-align:right;"> <b>Advanced System Assurance Pte Ltd</b><br>
						#09-23, UB One, 81 Ubi Avenue 4,<br>
						Singapore 408830<br>
						Tel: +65-6444 1218<br>
						www.asasg.com
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<p style="text-align:center; font-size:20px; margin-top:0;"> <u><b>PRELIMINARY PROPOSAL</b></u> </p>
					</div>
				</div>
				<div class="row">
					<div class="col-3">
						<b>Date</b> : <span><?= human_date($quotation->quote_date, 'd-M-Y') ?></span>
					</div>
					<div class="col-6" style="text-align:center;">
						<b>Quote Ref</b> : <span><?= $quotation->number ?></span>
					</div>
					<div class="col-3" style="text-align:right;">
						<b>Validity</b> : 60 Days
					</div>
				</div>
				<hr style="padding:0; margin:0; color:red">
			</div>
        </header>

        <footer>
			<hr style="padding:0; margin:0;">
			<b>Confidential property of Advanced System Assurance Pte Ltd</b>
        </footer>

        <main>
			<div class="container" style="page-break-after: always; font-size: 11px;">
				<div style="page-break-after: always; text-align:justify">
					<p>Dear <?= $quotation->contact->salutation . ' ' . $quotation->contact->name?>,</p>

					<p>Thanks for your keen interest in our training programme.</p>

					<p>We have pleasure in enclosing a preliminary proposal (Reference No: <?= $quotation->number ?>) covering
						training service for your organization. Please refer to our website for more details.</p>

					<p>We totally understand the importance of impartiality carrying out the management system
						assurance. In addition, we are fully committed to managing any conflict of interests in ensuring
						objectivity of the management system certification activities.</p>

					<p>We are always seeking involvement and continual improvement to serve the industries that meet
						the international standards and government regulatory requirements. We believe in value -added
						services to clients, through responsive certification services as well as auditor’s capabilities.</p>

					<p>Please note that all the certificates shall be issued by Advanced System Assurance Pte Ltd namely
						ASA. Certified clients shall be required to comply with all assessment and certification regulations
						as set by ASA.</p>

					<p>In acceptance of our proposal, we would greatly appreciate it, if you could kindly endorse the
						signature and return a copy of this letter to us via email.</p>

					<p>Please do not hesitate to contact us (6444 1218), if you require any further clarifications.</p>

					<p><b>Best Regards,</b></p>
					<img src="<?= assets_url('img/black_stamp.jpeg') ?>" width="150px" alt=""><br><br>
					<b><u>Ms. Viji - Sales and Marketing Manager</u></b><br>
					<b>H/P : </b>9455 4151<br>
					<b>Email : </b>viji@asasg.com<br>
					<b>DID : </b>6444 1218 <b>Ext:-</b> 101<br>
				</div>

				<p style="color:red"><b><u>ORGANIZATION DETAILS</u></b></p>
				<table class="table">
					<tbody>
						<tr>
							<td width="20%" style="text-align:right;"><b>Company Name:</b></td>
							<td><?= $quotation->client->name ?></td>
						</tr>
						<tr>
							<td width="20%" style="text-align:right;"><b>Company Address:</b></td>
							<td><?= full_address($quotation->address) ?></td>
						</tr>
						<tr>
							<td width="20%" style="text-align:right;"><b>Telephone Number:</b></td>
							<td><?= $quotation->address->phone ?></td>
						</tr>
						<tr>
							<td width="20%" style="text-align:right;"><b>Fax:</b></td>
							<td><?= $quotation->address->fax ?></td>
						</tr>
						<tr>
							<td width="20%" style="text-align:right;"><b>Website:</b></td>
							<td><?= $quotation->client->website ?></td>
						</tr>
					</tbody>
				</table>
				<br>

				<p style="color:red"><b><u>CONTACT PERSON DETAILS</u></b></p>
				<table class="table">
					<tbody>
						<tr>
							<td width="20%" style="text-align:right;"><b>Name:</b></td>
							<td><?= $quotation->contact->salutation . ' ' . $quotation->contact->name?></td>
						</tr>
						<tr>
							<td width="20%" style="text-align:right;"><b>Position:</b></td>
							<td><?= $quotation->contact->position ?></td>
						</tr>
						<tr>
							<td width="20%" style="text-align:right;"><b>H/P Number:</b></td>
							<td><?= $quotation->contact->mobile ?></td>
						</tr>
						<tr>
							<td width="20%" style="text-align:right;"><b>Email Address:</b></td>
							<td><?= $quotation->contact->email ?></td>
						</tr>
					</tbody>
				</table>
				<br>

				<div style="page-break-before: always; page-break-after: always">
					<p style="color:red"><b><u>FEE DETAILS</u></b></p>
					<table class="table">
						<tbody>
							<tr>
								<td width="15%"><b>Job:</b> </td>
								<td width="70%"><b>Payment Terms:</b></td>
								<td width="15%"><b>Duration:</b></td>
							</tr>
							<tr>
								<td>Interal Training (Max 20 Pax)</td>
								<td><?= $quotation->payment_terms ?></td>
								<td><?= $quotation->duration ?></td>
							</tr>
						</tbody>
					</table>
					<br>

					<table class="table">
						<tbody>
							<tr>
								<td width="15%"><b>#Phase</b></td>
								<td width="70%"><b>Descirption</b></td>
								<td width="15%"><b>Amount</b></td>
							</tr>
							<tr>
								<td>
									<u>Training</u><br>
									<?php foreach ($quotation->training_type_arr as $training_type) {?>
										-  <?= $training_type ?> <br>
									<?php }?>
								</td>
								<td><?= nl2br($quotation->training_description) ?></td>
								<td><?= money_number_format($quotation->total_amount, $quotation->address->country) ?></td>
							</tr>
							<tr>
								<td></td>
								<td><b>Discount</b></td>
								<td><?= money_number_format($quotation->discount, $quotation->address->country) ?></td>
							</tr>
							<tr>
								<td></td>
								<td><b>Total</b></td>
								<td><?= money_number_format($quotation->total_amount - $quotation->discount, $quotation->address->country) ?></td>
							</tr>
						</tbody>
					</table>
				</div>


				<div style="page-break-inside: avoid;">
					<p style="color:red"><b><u>PROPOSAL ACCEPTANCE</u></b></p>
					<span>We hereby acknowledge our company details and proposal offered above by Advanced System Assurance Pte Ltd.</span>
					<br><br>
					<table class="table">
						<tbody>
							<tr>
								<td width="20%" style="text-align:right;"><b>Person In Charge:</b></td>
								<td></td>
							</tr>
							<tr>
								<td width="20%" style="text-align:right;"><b>Contact Number:</b></td>
								<td></td>
							</tr>
							<tr>
								<td width="20%" style="text-align:right;"><b>Date:</b></td>
								<td></td>
							</tr>
							<tr>
								<td width="20%" style="text-align:right;"><b>Signature & Company Stamp:</b></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<br>
					<span>
						<b>Note</b>: Please return the signed copy of this proposal within 60 days of the date issued. If you are
							accepting the proposal after 60 days of issuance date with any changes in the above details, please
							get the revised proposal from us with new updates.
					</span>
				</div>
			</div>

			<div class="container" style="text-align:justify; font-size:11px;">
				<p style="color:red"><b><u>CONTRACT ACCEPTANCE TERMS & CONDITIONS</u></b></p>
				<p>This Agreement is entered into basis of Quote Ref: <?= $quotation->number ?>. It is further governed by the Advanced System Assurance Pte Ltd Terms & Condition,</p>
				<p>Terms & Conditions</p>
				<ul>
					<li>All prices are not subjected to Goods and Services Tax “GST”.</li>
					<li>A fee of $400 (exclusive GST) will be imposed for the issue of additional or replacement of training certificate of approval issued.</li>
				</ul>
				<br><br><br>

				<div style="text-align:right;">
					<b>Please return this proposal to:</b><br>
					<span style="color:blue">admin1@asasg.com /</span><br>
					<span style="color:blue">admin2@asasg.com /</span><br>
					<span style="color:blue">viji@asasg.com</span><br>
				</div>
			</div>
        </main>

		<script type="text/php">
		if (isset($pdf)) {
			$pdf->page_script('
				if ($PAGE_COUNT > 1) {
					$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
					$size = 8;
					$pageText = "Document No: F_47 ";
					$y = $pdf->get_height() - 22;
					$x = 30;
					$pdf->text($x, $y, $pageText, $font, $size);
				}
			');
		}

		if (isset($pdf)) {
			$pdf->page_script('
				if ($PAGE_COUNT > 1) {
					$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
					$size = 8;
					$pageText = "Revision: 03";
					$y = $pdf->get_height() - 22;
					$x = $pdf->get_width() - $fontMetrics->get_text_width($pageText, $font, $size) - 30;
					$pdf->text($x, $y, $pageText, $font, $size);
				}
			');
		}

		if (isset($pdf)) {
			$pdf->page_script('
				if ($PAGE_COUNT > 1) {
					$font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
					$size = 8;
					$pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
					$y = 15;
					$x = $pdf->get_width() - $fontMetrics->get_text_width($pageText, $font, $size) - 20;
					$pdf->text($x, $y, $pageText, $font, $size);
				}
			');
		}
		</script>
    </body>
</html>
