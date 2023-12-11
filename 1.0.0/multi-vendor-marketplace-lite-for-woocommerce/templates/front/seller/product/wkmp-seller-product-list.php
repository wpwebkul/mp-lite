<?php
/**
 * Seller product at front.
 *
 * @package Multi Vendor Marketplace
 *
 * @version 5.0.0
 */

defined( 'ABSPATH' ) || exit; // Exit if access directly.
?>
<form method="GET" id="wkmp-product-list-form" style="margin-bottom:unset;">
	<div class="wkmp-table-action-wrap">
		<div class="wkmp-action-section left">
			<input type="text" name="wkmp_search" placeholder="<?php esc_attr_e( 'Search Product', 'wk-marketplace' ); ?>" value="<?php echo isset( $filter_name ) ? esc_attr( wp_unslash( $filter_name ) ) : ''; ?>">
			<?php wp_nonce_field( 'wkmp_product_search_nonce_action', 'wkmp_product_search_nonce' ); ?>
			<input type="submit" value="<?php esc_attr_e( 'Search', 'wk-marketplace' ); ?>" data-action="search"/>
		</div>
		<div class="wkmp-action-section right wkmp-text-right">
			<?php if ( $wkmp_min_order_enabled || $wkmp_product_qty_limit_enabled ) { ?>
				<a id="wkmp_product_misc_settings" class="wkmp-minimum-order settings" href="javascript:void(0);"><?php esc_html_e( 'Miscellaneous Settings', 'wk-marketplace' ); ?></a>
			<?php } ?>
			<button type="button" data-form_id="#wkmp-delete-product" class="button wkmp-bulk-delete" title="<?php esc_attr_e( 'Delete', 'wk-marketplace' ); ?>">
				<span class="dashicons dashicons-trash"></span></button>&nbsp;&nbsp;
			<a href="<?php echo esc_url( get_permalink() . get_option( '_wkmp_add_product_endpoint', 'seller-add-product' ) ); ?>" class="button add-product" title="<?php esc_attr_e( 'Add Product', 'wk-marketplace' ); ?>"><span class="dashicons dashicons-plus-alt"></span></a>
		</div>
	</div>
</form>

<form action="" method="post" enctype="multipart/form-data" id="wkmp-delete-product" style="margin-bottom:unset;">
	<div class="wkmp-table-responsive wkmp-seller-products-lists">
		<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<td style="width:1px;"><input type="checkbox" id="wkmp-checked-all"></td>
				<td><?php esc_html_e( 'Image', 'wk-marketplace' ); ?></td>
				<td><?php esc_html_e( 'Name', 'wk-marketplace' ); ?></td>
				<td><?php esc_html_e( 'Stock', 'wk-marketplace' ); ?></td>
				<td><?php esc_html_e( 'Status', 'wk-marketplace' ); ?></td>
				<td><?php esc_html_e( 'Price', 'wk-marketplace' ); ?></td>
				<td style="width:17%;"><?php esc_html_e( 'Action', 'wk-marketplace' ); ?></td>
			</tr>
			</thead>
			<tbody>
			<?php
			if ( $products ) {
				foreach ( $products as $key => $product ) {
					?>
					<tr>
						<td><input type="checkbox" name="selected[]" value="<?php echo esc_attr( $product['product_id'] ); ?>"/></td>
						<td><img src="<?php echo esc_url( $product['image'] ); ?>" height="50" width="60" class="wkmp-img-thumbnail" style="display:unset;"/></td>
						<td>
							<?php
							if ( 'publish' === strtolower( $product['status'] ) ) {
								?>
								<a href="<?php echo esc_url( $product['product_href'] ); ?>"><?php echo esc_html( $product['name'] ); ?></a>
								<?php
							} else {
								echo esc_attr( $product['name'] );
							}
							?>
						</td>
						<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e( 'Stock', 'wk-marketplace' ); ?>"><?php echo esc_html( $product['stock'] ); ?>
							<?php
							if ( ! empty( $product['stock_quantity'] ) ) {
								echo esc_attr( '(' . $product['stock_quantity'] . ')' );
							}
							?>
						</td>
						<td class="woocommerce-orders-table__cell" data-title="<?php esc_attr_e( 'Status', 'wk-marketplace' ); ?>"><?php echo esc_html( $product['status'] ); ?></td>
						<td>
							<?php echo wp_kses_post( $product['price'] ); ?>
						</td>
						<td>
							<a href="<?php echo esc_url( $product['edit'] ); ?>" class="button"><span class="dashicons dashicons-edit"></span></a>
							<a href="javascript:void(0);" data-product_id="<?php echo esc_attr( $product['product_id'] ); ?>" class="button wkmp_delete_seller_product"><span class="dashicons dashicons-trash"></span></a>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan="7" class="wkmp-text-center"><?php esc_html_e( 'No Data Found', 'wk-marketplace' ); ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php wp_nonce_field( 'wkmp-delete-product-nonce-action', 'wkmp-delete-product-nonce' ); ?>
</form>

<?php
echo wp_kses_post( $pagination['results'] );
echo wp_kses_post( $pagination['pagination'] );

$amount_placeholder    = empty( $wkmp_min_order_amount ) ? esc_html__( 'No Restrictions.', 'wk-marketplace' ) : esc_html__( 'Enter minimum amount.', 'wk-marketplace' );
$qty_placeholder       = empty( $wkmp_max_product_qty ) ? esc_html__( 'No Restrictions.', 'wk-marketplace' ) : esc_html__( 'Enter maximum purchasable product quantity.', 'wk-marketplace' );
$clear_amount_btn_text = empty( $wkmp_min_order_amount ) ? esc_html__( 'Enable', 'wk-marketplace' ) : esc_html__( 'Clear', 'wk-marketplace' );
$clear_qty_btn_text    = empty( $wkmp_max_product_qty ) ? esc_html__( 'Enable', 'wk-marketplace' ) : esc_html__( 'Clear', 'wk-marketplace' );
$thousand_seperator    = wc_get_price_thousand_separator();
$wkmp_min_order_amount = (float) str_replace( $thousand_seperator, '', $wkmp_min_order_amount );
?>
<div id="wkmp_minimum_order_model" class="wkmp-min-order-popup wkmp-popup-modal">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"><?php esc_html_e( 'Product Miscellaneous Settings', 'wk-marketplace' ); ?></h4>
		</div>
		<div class="modal-body">
			<form action="" method="post" enctype="multipart/form-data" id="wkmp-seller-min-order-amount-form">
				<?php
				if ( $wkmp_min_order_enabled ) {
					?>
					<div class="form-group wkmp-popup-model">
						<div class="wkmp-width-45">
							<label for="wkmp-message"><b><?php esc_html_e( 'Minimum Order Amount Checkout', 'wk-marketplace' ); ?></b></label>&nbsp;
						</div>
						<div class="wkmp-width-45">
							<input placeholder="<?php echo esc_attr( $amount_placeholder ); ?>" data-empty_allow="<?php echo empty( $wkmp_min_order_amount ) ? 1 : 0; ?>" value="<?php echo esc_attr( $wkmp_min_order_amount ); ?>" type="number" step="0.01" min="1" value="" name="_wkmp_minimum_order_amount" <?php echo empty( $wkmp_min_order_amount ) ? 'readonly' : ''; ?> >
						</div>
						<div class="wkmp-width-10">
							<a id="wkmp_clear_min_order_amount" href="javascript:void(0);"><?php echo esc_html( $clear_amount_btn_text ); ?></a>
						</div>
					</div>
					<div id="wkmp-amount-error" class="wkmp-text-danger"></div>
					<?php
				}

				if ( $wkmp_product_qty_limit_enabled ) {
					?>
					<div class="form-group wkmp-popup-model">
						<div class="wkmp-width-45">
							<label for="wkmp-message"><b><?php esc_html_e( 'Maximum Purchasable Product Quantity', 'wk-marketplace' ); ?></b></label>&nbsp;
						</div>
						<div class="wkmp-width-45">
							<input placeholder="<?php echo esc_attr( $qty_placeholder ); ?>" data-empty_allow="<?php echo empty( $wkmp_max_product_qty ) ? 1 : 0; ?>" value="<?php echo esc_attr( $wkmp_max_product_qty ); ?>" type="number" step="1" min="0" value="" name="_wkmp_max_product_qty_limit" <?php echo empty( $wkmp_max_product_qty ) ? 'readonly' : ''; ?> >
						</div>
						<div class="wkmp-width-10">
							<a id="wkmp_clear_max_qty_limit" href="javascript:void(0);"><?php echo esc_html( $clear_qty_btn_text ); ?></a>
						</div>
					</div>
					<div id="wkmp-max-qty-limit-error" class="wkmp-text-danger"></div>
					<?php
				}
				wp_nonce_field( 'wkmp-min-order-nonce-action', 'wkmp-min-order-nonce' );
				?>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="button close-modal"><?php esc_html_e( 'Close', 'wk-marketplace' ); ?></button>
			<button id="wkmp-submit-min-order-amount-update" type="submit" form="wkmp-seller-min-order-amount-form" class="button"><?php esc_html_e( 'Save', 'wk-marketplace' ); ?></button>
		</div>
	</div>
</div>
<div class="wkmp-ajax-loader wkmp_hide"><div class="wkmp-loading-wheel"></div></div>

