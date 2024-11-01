<!-- First Tab content -->
<div id="klick_ual_tab_first">
		<div class="klick-notice-message"></div>
		<div class="wp-list-table widefat fixed striped klick-ual-list"> <!-- Klick tab specific notice starts -->
			<span id="message_lbl" class="info">
				<?php if (($options->is_configured_email_and_toggle() == false )) { 
					_e("No email notifications will be sent", "klick-ual"); 
					} else { 
					_e("You will get notification of login and logout activity at the following email address", "klick-ual");
					} ?> 
			</span>
			
			<div id="klick_setting_list" style="display: none;">
				<div id="klick_ual_table_email">
					<?php echo (($options -> get_option('email') != false) ? $options -> get_option('email') : '' ); ?>
				</div>
			</div>
		</div> <!-- Klick tab specific notice ends -->
	    <hr/>

	    <script type="text/javascript">
	        var klick_ual_ajax_nonce='<?php echo wp_create_nonce('klick_ual_ajax_nonce'); ?>';
	    </script>

	    <?php 
	    $email = $options -> get_option('email');
	    $email = (isset($email) ? $email : '' );
	     ?>
	
	    <div class="klick-ual-form-wrapper"> <!-- Form wrapper starts -->
			<form>
	            <table class="form-table">
	                <tbody>
	                    <p id="klick_ual_blank_error" class="klick-ual-error"></p>
	                    <tr>
	                        <th>
	                            <label for="klick_ual_email">Email : </label>
	                        </th>
	                        <td>
	                            <input class="regular-text" type="text" value="<?php echo $email; ?>" name="klick_ual_email" id="klick_ual_email" placeholder="Enter your e-mail">
	                            <span class="klick-ual-error-text"></span>
	                        </td>
	                    </tr>
	                    <tr>
	                        <th>
	                            <label for="klick_ual_email">On/Off : </label>
	                        </th>
	                        <td>
	                        	<?php $emailtoggle = $options -> get_option('send-email'); ?>
	                            ON : <input type="radio" name="klick_ual_email_toggle" value="<?php _e('ON','klick-ual'); ?>" class="klick-ual-send-email" <?php echo (!empty($emailtoggle) ? 'checked = "checked"' : '' ); ?>> 
	                            OFF : <input type="radio" name="klick_ual_email_toggle" value="<?php _e('OFF','klick-ual'); ?>" class="klick-ual-send-email" <?php echo (empty($emailtoggle) ? 'checked = "checked"' : '' ); ?>>
	                            <span class="klick-ual-error-text"></span>
	                        </td>
	                    </tr>
	                </tbody>
	            </table>
	        </form>

	        <p class="submit">
	            <button id="klick_btn_Save" name="klick_btn_Save" class="klick_btn button button-primary" disabled="disabled"><?php _e('Save','klick-ual'); ?></button>
	        </p>
	        
	    </div> <!-- Form wrapper ends -->
</div>
