<?php if(!empty($parale_message)): ?>
  <div id="message" class="updated fade"><?php echo $parale_message; ?></div>
<?php endif ?>
<div class="wrap">
	<h2><?php _e('2Parale for WordPress Configuration', '2Parale-for-wordpress'); ?></h2>
	<div class="narrow">
		<form id="2Parale-conf" style="margin: auto; width: 400px;" method="post" action=""> 
			<h3><?php _e('2Parale Username' , '2Parale-for-wordpress'); ?></h3>
			<p><input id="2Parale_user" name="2Parale_user" type="text" style="font-family: 'Courier New',Courier,mono; font-size: 1.5em;" value="<?php echo $this->options['2Parale_user']; ?>" size="25" /></p>
			<h3><?php _e('2Parale Password', '2Parale-for-wordpress'); ?></h3>
			<p><input id="2Parale_password" name="2Parale_password" type="password" style="font-family: 'Courier New',Courier,mono; font-size: 1.5em;" value="<?php echo $this->options[ '2Parale_password' ]; ?>" size="25" /></p>
			<?php wp_nonce_field( '2Parale-for-wordpress-save_options' ); ?>
			<p class="submit"><input id="2Parale-submit" name="2Parale-submit" type="submit" value="<?php _e( 'Update Options' , '2Parale-for-wordpress'); ?>" name="submit" /></p>
		</form>
	</div>
</div>