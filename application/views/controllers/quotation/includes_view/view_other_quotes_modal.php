<div class="modal fade" id="view-other-quotes-modal">
	<div class="modal-dialog modal-xl sidebar-sm">
		<div class="modal-content">
		<div class="modal-header mb-1">
				<h5 class="modal-title">Other Quotes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
			</div>
			<div class="modal-body flex-grow-1">
				<div class="tab-content">
					<?php if ($other_quotes) { ?>
						<?php foreach ($other_quotes as $quote): ?>
							<?php if ($quote->id != $quotation->id): ?>
								<?php if ($quote->type == 'ISO'): ?>
									<?php include 'other_quotes_iso.php' ?>
								<?php endif; ?>
	
								<?php if ($quote->type == 'Bizsafe'): ?>
									<?php include 'other_quotes_bizsafe.php' ?>
								<?php endif; ?>
	
								<?php if ($quote->type == 'Training'): ?>
									<?php include 'other_quotes_training.php' ?>
								<?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php } else { echo '<h4>No other quotation from this client</h4>'; } ?>
					
				</div>
			</div>
		</div>
	</div>
</div>
