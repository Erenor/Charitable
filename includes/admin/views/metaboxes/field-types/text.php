<?php
/**
 * Display text field.
 *
 * @author    Eric Daams
 * @package   Charitable/Admin Views/Metaboxes
 * @copyright Copyright (c) 2018, Studio 164a
 * @since     1.2.0
 * @version   1.6.0
 */

if ( ! array_key_exists( 'form_view', $view_args ) || ! $view_args['form_view']->field_has_required_args( $view_args ) ) {
	return;
}

?>
<div id="<?php echo esc_attr( $view_args['wrapper_id'] ); ?>" class="<?php echo esc_attr( $view_args['wrapper_class'] );?>">
	<?php if ( isset( $view_args['label'] ) ) : ?>
		<label for="<?php echo esc_attr( $view_args['id'] ); ?>"><?php esc_html_e( $view_args['label'] )  ?></label>
	<?php endif ?>
	<input type="text" 
		id="<?php echo esc_attr( $view_args['id'] ); ?>" 
		name="<?php echo esc_attr( $view_args['key'] ); ?>"
		value="<?php echo esc_attr( $view_args['value'] ); ?>"
		tabindex="<?php echo esc_attr( $view_args['tabindex'] ); ?>"
		placeholder="<?php echo esc_attr( $view_args['placeholder'] ); ?>" />
	<?php if ( isset( $view_args['description'] ) ) : ?>
		<span class="charitable-helper"><?php esc_html_e( $view_args['description'] ); ?></span>
	<?php endif ?>
</div>
