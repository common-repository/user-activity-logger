/**
 * Send an action via admin-ajax.php
 * 
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
var send_command = function (action, data, callback, json_parse) {
	json_parse = ('undefined' === typeof json_parse) ? true : json_parse;
	var ajax_data = {
		action: 'klick_ual_ajax',
		subaction: action,
		nonce: klick_ual_ajax_nonce,
		data: data
	};
	jQuery.post(ajaxurl, ajax_data, function (response) {
		
		if (json_parse) {
			try {
				var resp = JSON.parse(response);
			} catch (e) {
				console.log(e);
				console.log(response);
				return;
			}
		} else {
			var resp = response;
		}
		
		if ('undefined' !== typeof callback) callback(resp);
	});
}

/**
 * When DOM ready
 * 
 */
jQuery(document).ready(function ($) {
	klick_Ual = klick_Ual(send_command);
});

/**
 * Function for sending communications
 * 
 * @callable sendcommandCallable
 * @param {string} action - the action to send
 * @param * data - data to send
 * @param Callback [callback] - will be called with the results
 * @param {boolean} [json_parse=true] - JSON parse the results
 */
/**
 * Main klick_Ual
 * 
 * @param {sendcommandCallable} send_command
 */
var klick_Ual = function (send_command) {
	var $ = jQuery;

	// initialize DOM in tabs area
	var init_tabs = function(){
		if (($(".klick-ual-send-email:checked").val() !== "OFF")) {
			$("#klick_setting_list").css('display','block');
		}
	}

	init_tabs();
	
	// Disable/enable save when email change
	$("#klick_ual_email").keyup(function(){
		var emailaddress = $(this).val();
			if( isValidEmailAddress(emailaddress)) { 
				$("#klick_btn_Save").css('opacity',1);
				$("#klick_btn_Save").prop('disabled','');
			} else{
				$("#klick_btn_Save").css('opacity',0.5);
				$("#klick_btn_Save").prop('disabled','disabled');
			}
	});

	// Test for valid email address
	function isValidEmailAddress(emailAddress) {
	   return /^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(emailAddress)&& /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(emailAddress);
	}

	// Email toggle change handler
	$(".klick-ual-send-email").change(function(){
		var emailaddress = $("#klick_ual_email").val();
			if( isValidEmailAddress(emailaddress)) { 
				$("#klick_btn_Save").prop('disabled','');
			} else{
				$("#klick_btn_Save").prop('disabled','disabled');
			}
	});

	/**
	 * Gathers the details from form
	 * 
	 * @returns (string) - serialized row data
	 */
	function gather_row(){
		var form_data = $(".klick-ual-form-wrapper form").serialize();
		return form_data;	
	}

	// Send 'klick_Ual_save_settings' command, Response handler
	$("#klick_btn_Save").click(function() {
		$which_button = $(this).text();
		if($which_button == "Save") {
			$(this).prop('disabled','disabled');
			var form_data = gather_row();
			send_command('klick_ual_save_settings', form_data, function (resp) {
				if (resp.status['status'] == "1") {
					if (($(".klick-ual-send-email:checked").val() == "ON")) {
						$(".klick-ual-list").slideUp(200,function(){
							$("#message_lbl").html("<i> You will get notification of login and logout activity at the following email address </i>");
							$("#klick_ual_table_email").html(resp.status['data']['email']);
							$("#klick_setting_list").css("display","block");
							$(this).slideDown();
						});
					} else {
						$(".klick-ual-list").slideUp(200,function(){
							$("#message_lbl").html("<i> No email notifications will be sent </i>");
							$("#klick_setting_list").css("display","none");
							$(this).slideDown();
						});
					}
				}

				$('.klick-notice-message').html(resp.status['messages']);
				$('.fade').delay(2000).slideUp(200, function(){
					$("#klick_btn_Save").prop('disabled','disabled');
				});
			
			});
		}
	});
	
	/**
	 * Proceses the tab click handler
	 *
	 * @return void
	 */
	$('#klick_ual_nav_tab_wrapper .nav-tab').click(function (e) {
		e.preventDefault();
		
		var clicked_tab_id = $(this).attr('id');
	
		if (!clicked_tab_id) { return; }
		if ('klick_ual_nav_tab_' != clicked_tab_id.substring(0, 18)) { return; }
		
		var clicked_tab_id = clicked_tab_id.substring(18);

		$('#klick_ual_nav_tab_wrapper .nav-tab:not(#klick_ual_nav_tab_' + clicked_tab_id + ')').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');

		$('.klick-ual-nav-tab-contents:not(#klick_ual_nav_tab_contents_' + clicked_tab_id + ')').hide();
		$('#klick_ual_nav_tab_contents_' + clicked_tab_id).show();
	});
}
