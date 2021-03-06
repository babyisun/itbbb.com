<?php
//////// Theme Options
function olo_theme_options_items() {
	$items = array (
		array(
			'id' => 'is_olo_icp',
			'name' => __('Show ICP No.', 'olo'),
			'desc' => __('Enable ICP', 'olo'),
			'std'    => '',
			'hr'     => '',
			'nTable' => '',
			'nTitle' => '',
			'type'   => 'checkbox'
		),
		array(
			'id'     => 'olo_icp',
			'name'   => __('ICP No.', 'olo'),
			'desc'   => __('Put your ICP No. here.', 'olo'),
			'std'    => '',
			'hr'     => '',
			'nTable' => '',
			'nTitle' => '',
			'type'   => ''
		)
	);
	return $items;
}

add_action( 'admin_init', 'olo_theme_options_init' );
add_action( 'admin_menu', 'olo_theme_options_add_page' );
function olo_theme_options_init(){
	register_setting( 'olo_options', 'olo_theme_options', 'olo_options_validate' );
}
function olo_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'olo' ), __( 'Theme Options', 'olo' ), 'edit_theme_options', 'theme_options', 'olo_theme_options_do_page' );
}
function olo_default_options() {
	$options = get_option( 'olo_theme_options' );
	foreach ( olo_theme_options_items() as $item ) {
		if ( ! isset( $options[$item['id']] ) ) {
			if ( !empty($item['std']) )
				$options[$item['id']] = $item['std'];
			else
				$options[$item['id']] = '';
		}
	}
	update_option( 'olo_theme_options', $options );
}
add_action( 'init', 'olo_default_options' );
function olo_theme_options_do_page() {
	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
	if( isset( $_REQUEST['action'])&&('reset' == $_REQUEST['action']) ) {
		delete_option( 'olo_theme_options' );
		olo_default_options();
	}
?>
	<div class="wrap olo_wrap">

		<style>
			.olo_wrap label{cursor:text;}
			.has-sidebar-content{overflow:hidden;}
			.stuffbox h3{border-bottom:1px solid #e5e5e5;}
			.form-table, .form-table td, .form-table th, .form-table td p, .form-wrap label{font-size:12px;}
		</style>

		<?php screen_icon(); ?>
		<h2><?php echo sprintf( __( '%1$s Theme Options', 'olo' ), wp_get_theme() ); ?></h2>
		<?php settings_errors(); ?>
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div class="inner-sidebar">
				<div style="position:relative;" class="meta-box-sortabless ui-sortable" id="side-sortables">
					<div class="postbox" id="sm_pnres">
								<h3 class="hndle"><span><?php _e('Donation','olo'); ?></span></h3>
								<div class="inside" style="margin:0;padding-top:10px;background-color:#ffffe0;">
										<?php printf(__('Created, Developed and maintained by %s . If you feel my work is useful and want to support the development of more free resources, you can donate me. Thank you very much!','olo'), '<a href="'.esc_url( __( 'http://hjyl.org/', 'olo' ) ).'">HJYL</a>'); ?>
											<br /><br />
											<table>
											<td>
											<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
												<input type="hidden" name="cmd" value="_xclick">
												<input type="hidden" name="business" value="i@hjyl.org">
												<input type="hidden" name="item_name" value="olo WordPress Theme">
												<input type="hidden" name="charset" value="utf-8" >
												<input type="hidden" name="currency_code" value="USD">
												<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
											</form>
											</td>
											<td>
											<a href="<?php echo esc_url( __( 'http://me.alipay.com/hjyl', 'olo' ) ); ?>"><?php _e('Alipay', 'olo'); ?></a>
											</td>
											</table>
								</div>
						</div>
				</div>
			</div>
			<div class="has-sidebar-content" id="post-body-content">
				<form method="post" action="options.php">
					<?php settings_fields( 'olo_options' ); ?>
					<?php $options = get_option( 'olo_theme_options' ); ?>
					<div class="stuffbox" style="padding-bottom:10px;">
						<h3><label for="link_url"><?php _e( 'General settings', 'olo' ); ?></label></h3>
						<div class="inside">
							<table class="form-table">
							<?php foreach (olo_theme_options_items() as $item) {
							
								$olo_name = $item['name'];
								$olo_form_name = 'olo_theme_options['.$item['id'].']';
								$olo_value = !empty($options[$item['id']]) ? $options[$item['id']] : $item['std'];
							?>

								<?php if ($item['type'] == 'checkbox') { ?>
								
		<?php if ($item['nTable']) { ?>
							</table>
						</div>
					</div>
					<div class="stuffbox" style="padding-bottom:10px;">
						<h3><label for="link_url"><?php echo $item['nTitle']; ?></label></h3>
						<div class="inside">
							<table class="form-table">
		<?php } ?>
									<tr valign="top">
										<th scope="row"><strong><?php echo $olo_name; ?></strong></th>
										<td>
											<input name="<?php echo $olo_form_name; ?>" type="checkbox" value="1" <?php checked($olo_value, 1); ?> />
											<label class="description" for="<?php echo $olo_form_name; ?>"><?php echo $item['desc']; ?></label>
										</td>
									</tr>
									<?php if ($item['hr']) echo '<tr valign="top"><th style="margin:0;padding:0;border-bottom:1px solid #ddd;"> </th><td style="margin:0;padding:0;border-bottom:1px solid #ddd;"> </td></tr>'; ?>

								<?php } elseif ($item['type'] == 'code') { ?>
								
		<?php if ($item['nTable']) { ?>
							</table>
						</div>
					</div>
					<div class="stuffbox" style="padding-bottom:10px;">
						<h3><label for="link_url"><?php echo $item['nTitle']; ?></label></h3>
						<div class="inside">
							<table class="form-table">
		<?php } ?>
									<tr valign="top">
										<th scope="row"><strong><?php echo $olo_name; ?></strong></th>
										<td>
											<textarea name="<?php echo $olo_form_name; ?>" type="code" cols="65%" rows="4"><?php echo $olo_value; ?></textarea>
											<br/>
											<label class="description" for="<?php echo $olo_form_name; ?>"><?php echo $item['desc']; ?></label>
										</td>
									</tr>
									<?php if ($item['hr']) echo '<tr valign="top"><th style="margin:0;padding:0;border-bottom:1px solid #ddd;"> </th><td style="margin:0;padding:0;border-bottom:1px solid #ddd;"> </td></tr>'; ?>

								<?php } else { ?>
								
		<?php if ($item['nTable']) { ?>
							</table>
						</div>
					</div>
					<div class="stuffbox" style="padding-bottom:10px;">
						<h3><label for="link_url"><?php echo $item['nTitle']; ?></label></h3>
						<div class="inside">
							<table class="form-table">
		<?php } ?>
									<tr valign="top">
										<th scope="row"><strong><?php echo $olo_name; ?></strong></th>
										<td>
											<input name="<?php echo $olo_form_name; ?>" type="text" value="<?php echo $olo_value; ?>" size="40" />
											<br/>
											<label class="description" for="<?php echo $olo_form_name; ?>"><?php echo $item['desc']; ?></label>
										</td>
									</tr>
									<?php if ($item['hr']) echo '<tr valign="top"><th style="margin:0;padding:0;border-bottom:1px solid #ddd;"> </th><td style="margin:0;padding:0;border-bottom:1px solid #ddd;"> </td></tr>'; ?>

								<?php } ?>

							<?php } ?>
							</table>
						</div>
					</div>
					<p class="submit" style="margin:0;padding:0;">
						<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'olo' ); ?>" />
					</p>
				</form>
				<form method="post" style="position:relative;margin:0;padding:0;">
					<input class="button" name="reset" type="submit" value="<?php _e('Reset All Settings','olo'); ?>" onclick="return confirm('<?php _e('Click OK to reset. Any settings will be lost!', 'olo'); ?>');" style="position:absolute;left:120px;top:-28px;" />
					<input type="hidden" name="action" value="reset" />
				</form>
			</div>
		</div>
	</div>
<?php
}

//	Sanitize and validate input. Accepts an array, return a sanitized array.
function olo_options_validate( $input ) {

	// Say our checkbox option must be a positive number
	$input['is_olo_icp'] = absint( $input['is_olo_icp'] );
	
	// must be safe text with no HTML tags
	$input['olo_icp'] = sanitize_text_field( $input['olo_icp'] );

	return $input;
}
