<?php
	$quotation = $this->session->flashdata('quotation');
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<style>
		/** Define the margins of your page **/
		@page {
			margin-top: 200px;
			margin-bottom: 50px;
			margin-right: 30px;
			margin-left:  30px;
		}

		html, body { font-family: "Roboto", Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.4; font-weight: 400; color: #000000;}

		.row {
			display: table;
			width: 100%;
			/* table-layout: fixed; */
			padding: 0;
			margin:0;
		}
		.col-1 {
			display: table-cell;
			width: 8.33%; /* 1 column out of 12, assuming you are using a 12-column grid system */
			padding: 0;
			margin: 0;
		}
		.col-2 {
			display: table-cell;
			width: 16.67%;
			padding: 0;
			margin: 0;
		}
		.col-3 {
			display: table-cell;
			width: 25%;
		}
		.col-4 {
			display: table-cell;
			width: 33.3333%;
		}
		.col-5 {
			display: table-cell;
			width: 41.6667%;
			padding: 0;
			margin: 0;
		}
		.col-6 {
			display: table-cell;
			width: 50%;
		}
		.col-7 {
			display: table-cell;
			width: 58.33%; /* 7 columns out of 12, assuming you are using a 12-column grid system */
			padding: 0;
			margin: 0;
		}
		.col-8 {
			display: table-cell;
			width: 66.67%;
			padding: 0;
			margin: 0;
		}
		.col-9 {
			display: table-cell;
			width: 75%; /* 9 columns out of 12, assuming you are using a 12-column grid system */
			padding: 0;
			margin: 0;
		}
		header {
			position: fixed;
			top: -180px;
			left: 0px;
			right: 0px;
		}

		footer {
			position: fixed;
			bottom: 15px;
			left: 0px;
			right: 0px;
			height: 40px;
			text-align: center;
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
			background-color: red;
			color: white;
		}
		.bg-success {
			background-color: green;
		}
		.bg-danger {
			background-color: red;
		}
		.table-sm {
			font-size: 0.875rem;
		}

		/* Compact table styles */
		.table-sm td, .table-sm th {
			padding: 0.3rem;
		}
		.text-white {
			color: white;
		}
		.bg-secondary {
			background-color: #F0EDED; /* Replace this with your desired secondary color */
		}

		.h2 {
		  font-size: 2rem;
		  margin-bottom: 0.5rem;
		  font-weight: 500;
		  line-height: 1.2;
		}
	</style>
</head>
<body>
	<!-- Define header and footer blocks before your content -->
	<header>
		<div class="letter-header" style="font-size:12px;">
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
				<div class="col-12" style="padding:0; margin:0;">
					<center><b style="font-size:30px; text-align:center;">INVOICE</b></center>
				</div>
			</div>
			<hr style="padding:0; margin:0; color:red">
		</div>
	</header>

	<footer style="">
		<!-- <div class="col-12" style="padding:0; margin:0;"> -->
			<center>
				<h5 style="color:grey">
					THANK YOU VERY MUCH FOR YOUR BUSINESS <br>
					Should you have any enquiries concerning this invoice, please contact admin @ 6444 1218
				</h5>
			</center>
		<!-- </div> -->
		<hr style="padding:0; margin:0; color:red; margin-bottom:10px;">
		<b>Confidential property of Advanced System Assurance Pte Ltd</b>
	</footer>

	<main>
		<div class="container" style="font-size: 11px;">
			<div class="row">
				<div class="col-3">
					<h2>INVOICED TO</h2>
					<?php if ($invoice->quotation->invoice_to == 'Consultant' && $invoice->quotation->consultant_pay_3_years == 'Yes'): ?>
						<span style="font-size: 12px;"><?= $invoice->quotation->referred_by ?></span> <br>
					<?php else: ?>
						<?php if ($invoice->quotation->invoice_to == 'Consultant' && $invoice->quotation->consultant_pay_3_years == 'No' && $invoice->num_order == 1): ?>
							<span style="font-size: 12px;"><?= $invoice->quotation->referred_by ?></span> <br>
						<?php else: ?>
							<span style="font-size: 12px;"><?= $invoice->contact->salutation . ' ' . $invoice->contact->name ?></span> <br>
							<span style="font-size: 12px;"><?= $invoice->client->name ?></span> <br>
							<span style="font-size: 12px;"><?= full_address($invoice->address) ?></span> <br>
							<?php if ($invoice->contact->mobile): ?>
								<span style="font-size: 12px;">Tel: <?= $invoice->contact->mobile ?></span>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-3">
					<b style="font-size:18px; margin-bottom: 0;">INVOICE DETAILS</b>
				</div>
				<div class="col-6 bg-danger" style="padding-left:10px;">
					<b class="text-white">DESCRIPTION</b>
				</div>
				<div class="col-1 bg-danger" style="padding:0px 15px 0px 10px; text-align:right;">
					<b class="text-white">GST</b>
				</div>
				<div class="col-2 bg-danger" style="padding:0px 15px 0px 10px; text-align:right;">
					<b class="text-white">AMOUNT</b>
				</div>
			</div>
			<div class="row" style="margin-top:8px;">
				<div class="col-3">
					<br>
					<b style="font-size:15px; color:grey">INVOICE NUMBER</b><br>
					<b style="font-size:13px;"><?= $invoice->number ?></b>
					<br><br>

					<b style="font-size:15px; color:grey">DATE OF ISSUE</b><br>
					<b style="font-size:13px;"><?= human_date($invoice->invoice_date, 'd-m-Y') ?></b>
					<br><br>

					<b style="font-size:15px; color:grey">QUOTE REF NUMBER</b><br>
					<b style="font-size:13px;"><?= $invoice->quotation->number ?></b>
				</div>
				<div class="col-6" style="padding:15px 15px 0px 10px; background-color:#f3f3f3">
					<?php
					if ($invoice->quotation->type == 'ISO') {
						$fee_type = 'Fees';
						$scheme_arr = $invoice->quotation->certification_scheme_arr;
						$bizsafe_scheme = '';
						$non_bizsafe_scheme = [];

						foreach ($scheme_arr as $val) {
				            if (stripos(strtolower($val), 'bizsafe') !== false) {
				                $bizsafe_scheme = $val;
				            } else {
								array_push($non_bizsafe_scheme, $val);
				            }
				        }

				        if (count($non_bizsafe_scheme) > 1) {
				            $lastElement = array_pop($non_bizsafe_scheme); // Remove the last element
				            $full_scheme = implode(', ', $non_bizsafe_scheme) . ' & ' . $lastElement;
				        } else {
				            $full_scheme = implode(', ', $non_bizsafe_scheme);
				        }

						if ($invoice->quotation->client_pay_3_years == 'Yes') {
							$full_scheme .= ' - <br> Stage 1 & Stage 2, Surveillance 1 & Surveillance 2 Audit Fees (Paying for 3 years)';
						} else {
							if ($invoice->invoice_type == '1st Year Surveillance' || $invoice->invoice_type == '2nd Year Surveillance') {
								$full_scheme .= ' - ' . $invoice->invoice_type . ' Audit Fees';
							} else {
								$full_scheme .= ' - ' . $invoice->invoice_type . ' Fees';
							}
						}


						if ($bizsafe_scheme && ($invoice->invoice_type != '1st Year Surveillance' && $invoice->invoice_type != '2nd Year Surveillance')) {
							$full_scheme = $full_scheme . ' & ' . $bizsafe_scheme . ' Audit Fees';
						}

						// if ($bizsafe_scheme) {
						// 	echo $full_scheme . ' & ' . $bizsafe_scheme . ' Audit Fees';
						// } else {
						// 	echo $full_scheme;
						// }
						echo $full_scheme;

					} elseif ($invoice->quotation->type == 'Bizsafe') {
						echo 'BizSAFE RM Audit Fees';
					} else {
						$fee_type = 'Training Fees';
						$training_arr = $invoice->quotation->training_type_arr;
						if (count($training_arr) > 1) {
						    $lastElement = array_pop($training_arr); // Remove the last element
						    $full_training = implode(', ', $training_arr) . ' & ' . $lastElement;
						} else {
						    $full_training = implode(', ', $training_arr);
						}
						echo $full_training . ' ' . $fee_type;
					}
					?>

					<?php if ($invoice->quotation->invoice_to == 'Consultant'): ?>
						<br>
						<hr style="padding:0; margin:0; color:white">
						<br>
						<span style="font-size: 12px;">For the client :</span> <br>
						<span style="font-size: 12px;"><?= $invoice->contact->salutation . ' ' . $invoice->contact->name ?></span> <br>
						<span style="font-size: 12px;"><?= $invoice->client->name ?></span> <br>
						<span style="font-size: 12px;"><?= full_address($invoice->address) ?></span> <br>
						<span style="font-size: 12px;">Tel: <?= $invoice->contact->mobile ?></span>
					<?php endif; ?>

					<?php if ($invoice->quotation->num_of_sites > 1): ?>
						<br>
						<hr>
						<?php $no = 2; foreach ($invoice->quotation->other_sites as $i => $address): ?>
							<?php if ($i <= 5): ?>
								<p>
									Site Address : <?= $no++; ?> <br>
									<?= $address->address->name ?> <br>
									<?= full_address($address->address) ?>
								</p>
							<?php $i++; else: break; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="col-1" style="padding:15px 15px 0px 10px; text-align:right; background-color:#f3f3f3">
					N/A
				</div>
				<div class="col-2" style="padding:15px 15px 0px 10px; text-align:right; background-color:#f3f3f3">
					<?= money_number_format($invoice->amount, $invoice->address->country) ?>
				</div>
			</div>

			<?php if (isset($i)): ?>
				<?php
				$other_sites_left = count($invoice->quotation->other_sites) - $i;
				$loop = ceil($other_sites_left / 8);
				for ($i_loop = 1; $i_loop <= $loop; $i_loop++) { ?>
					<div class="row" style="margin-top:8px;">
						<div class="col-3">
						</div>
						<div class="col-6" style="padding:0px 15px 0px 10px; background-color:#f3f3f3">
							<?php
								if ($i_loop == $loop) {
									$max = count($invoice->quotation->other_sites);
								} else {
									$max = (8 * ($i_loop+1)) - 2;
								}
								for ($i = $i; $i < $max; $i++) {
									$address = $invoice->quotation->other_sites[$i];
								?>
								<p>
									Site Address : <?= $no++; ?> <br>
									<?= $address->address->name ?> <br>
									<?= full_address($address->address) ?>
								</p>
							<?php }	?>
						</div>
						<div class="col-1" style="padding:15px 15px 0px 10px; text-align:right; background-color:#f3f3f3">
						</div>
						<div class="col-2" style="padding:15px 15px 0px 10px; text-align:right; background-color:#f3f3f3">
						</div>
					</div>
					<br><br>
				<?php } ?>
			<?php endif; ?>
		</div>

		<div style="page-break-inside:avoid">
			<div class="row" style="margin-top:8px;">
				<div class="col-3">
					<b style="font-size:13px; color:grey">PAYEE NAME</b>
					<br><br>

					<b style="font-size:13px; color:grey">BANK ACCOUNT NO</b>
					<br><br>

					<b style="font-size:13px; color:grey">BANK</b>
					<br><br>

					<b style="font-size:13px; color:grey">ACCOUNT TYPE</b>
					<br><br>

					<b style="font-size:13px; color:grey">PAY NOW</b>
				</div>
				<div class="col-6">
					<b style="font-size:13px;">Advanced System Assurance Pte Ltd</b>
					<br><br>

					<b style="font-size:13px;">106-904-255-0</b>
					<br><br>

					<b style="font-size:13px;">DBS BANK LTD</b>
					<br><br>

					<b style="font-size:13px;">Current</b>
					<br><br>

					<b style="font-size:13px;">201609440C</b>
				</div>
				<div class="col-1">
					<b style="font-size:13px; color:grey">SUBTOTAL</b>
					<br><br>

					<b style="font-size:13px; color:grey">DISCOUNT</b>
					<br><br><br>

					<b style="font-size:13px; color:grey">TOTAL</b>

				</div>
				<div class="col-2" style="text-align:right">
					<b style="font-size:15px;">
						<?= money_number_format($invoice->amount, $invoice->address->country) ?>
					</b>
					<br><br>

					<b style="font-size:15px;">
						<?= money_number_format(0) ?>
					</b>
					<br><br><br>

					<b style="font-size:15px;">
						<?= money_number_format($invoice->amount, $invoice->address->country) ?>
					</b>
				</div>
			</div>

			<div class="row" style="margin-top:8px;">
				<div class="col-3">
				</div>
				<div class="col-5">
					<img class="image" src="<?= base_url('assets/img/qrcode.png') ?>" style="width:130px; padding:0; margin:0;">
				</div>
				<div class="col-4" style="text-align:right">
					<b style="font-size:15px;">
						Best Regard,
						<br><br>
						<img src="<?= assets_url('img/black_stamp.jpeg') ?>" width="150px" alt=""><br><br>
						Viji- Sales and Marketing Manager
					</b>
				</div>
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
