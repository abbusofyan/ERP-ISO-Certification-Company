<?php
	$quotation = $this->session->flashdata('quotation');
?>
<html>
    <head>
		<title><?= $memo->number ?></title>
        <style>
            /** Define the margins of your page **/
			@page {
			    margin-top: 130px;
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
				top: -110px;
				left: 0px;
				right: 0px;
			}

            footer {
                position: fixed;
                bottom: -40px;
                left: 0px;
                right: 0px;
                height: 50px;

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

			.namecard {
				position: fixed;
                left: 0px;
                right: 0px;
				bottom: -70px;
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
				<hr style="padding:0; margin:0; color:red">
			</div>
        </header>
		<br><br>
        <main>
			<div class="container">
				<div class="row">
					<p><b class="a">Address</b>  <span class="b">:</span>  <span class="c"><?= full_address($memo->quotation->address) ?></span></p>
					<p><b class="a">Company</b>  <span class="b">:</span>  <span class="c"><?= $memo->quotation->client->name ?></span></p>
					<p><b class="a">Reference</b>  <span class="b">:</span>  <span class="c"><?= $memo->number ?></span></p>
					<p><b class="a">Date</b>  <span class="b">:</span>  <span class="c"><?= $memo->memo_date ?></span></p>
				</div>
				<br>
				<div class="row">
					<div class="col-12" style="padding:0; margin:0;">
						<center><b style="font-size:15px; text-align:center;">TO WHOM IT MAY CONCERN</b></center>
					</div>
				</div>
				<br>
				<?= html_entity_decode($memo->message) ?>
				<br><br><br>
			</div>
        </main>

		<div class="namecard">
			<?php if ($memo->status == 'Approved'): ?>
				<p>Yours Sincerely</p>
				<img src="<?= assets_url('img/blue_stamp.jpeg') ?>" width="150px" alt=""><br><br>
			<?php endif; ?>
			<img src="<?= assets_url('img/namecard.png') ?>" width="400px" alt="">
		</div>

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
