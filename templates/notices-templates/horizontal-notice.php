<?php if (!defined('KLICK_UAL_PLUGIN_MAIN_PATH')) die('No direct access allowed'); ?>


<div class="klick-ual-notice-container-wrapper" id = "<?php echo 'notice' . $notice_id; ?>"> <!-- Notice main container starts-->
	<div class="klick-ual-notice-container">


		<div class="klick-ual-notice-content-left"> <!-- Notice image logo starts -->
			<img src="<?php echo KLICK_UAL_PLUGIN_URL . '/images/' . $image_url; ?>" width="60" height="60" alt="<?php _e('notice image', 'klick-ual'); ?>" />
		</div> <!-- Notice image logo ends -->


		<div class="klick-ual_notice_content_wrapper"> <!-- Notice content wrapper starts -->
			<h3 class="klick-ual-notice-heading"> <!-- Notice heading starts -->
				<?php echo $title;?>
				<div class="klick-ual-notice-dismiss">
					<?php
					if ($dismiss_type == "dismiss") { ?>
						<a href="#"  onclick="jQuery('#notice<?php echo $notice_id; ?>').slideUp(); jQuery.post(ajaxurl, { action: 'klick_ual_ajax', data: '<?php echo $notice_id; ?>', subaction: 'dismiss_page_notice_until', nonce: '<?php echo wp_create_nonce('klick_ual_ajax_nonce'); ?>' });"><?php echo $dismiss_text; ?></a>
					<?php } else { ?>
							<a href="#"  onclick="jQuery('#notice<?php echo $notice_id; ?>').slideUp(); jQuery.post(ajaxurl, { action: 'klick_ual_ajax', data: '<?php echo $notice_id; ?>', subaction: 'dismiss_page_notice_until_forever', nonce: '<?php echo wp_create_nonce('klick_ual_ajax_nonce'); ?>' });"><?php echo $dismiss_text; ?></a>
					<?php }  ?>
				</div>
			</h3> <!-- Notice heading ends -->
			
			<p> <!-- Notice text starts -->
				<?php
					echo $notice_text;
					if (!empty($button_link) && !empty($button_text)) {
						
						// Check which Message is going to be used.
						$klick_ual->get_dashboard()->klick_ual_url($button_link, $button_text, null, 'class="klick-ual-notice-link"');
					}
				?>
			</p> <!-- Notice text ends -->
		</div> <!-- Notice content wrapper ends -->
	</div>
	<div class="clear"></div>
</div> <!-- Notice main container ends-->

