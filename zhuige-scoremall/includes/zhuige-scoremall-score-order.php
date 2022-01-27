<?php

/*
 * 追格积分商城Free
 * Author: 追格
 * Help document: https://www.zhuige.com/owfree/7685.html
 * github: https://github.com/longwenjunjie/zhuige_scoremall
 * gitee: https://gitee.com/longwenjunj/zhuige_scoremall
 * License：GPL-2.0
 * Copyright © 2022 www.zhuige.com All rights reserved.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

require dirname( __FILE__ ) . '/class-zhuige-scoremall-score-order-list.php';

add_action('admin_menu', 'zhuige_scoremall_add_score_order_menu');
function zhuige_scoremall_add_score_order_menu() {
	add_menu_page(
		'积分兑换订单', 			// Page title.
		'积分兑换订单',        		// Menu title.
		'activate_plugins',			// Capability.
		'zhuige_scoremall_score_order',			// Menu slug.
		'zhuige_scoremall_render_score_order',		// Callback function.
		'',
		3
	);
}

function zhuige_scoremall_render_score_order() {
	$action = isset($_GET['action']) ? $_GET['action'] : '';
	if ('edit' == $action) {
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$order = [];
		global $wpdb;
		$table_score_order = $wpdb->prefix . 'zhuige_scoremall_score_order';
		$order = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_score_order WHERE id=%d", $id), ARRAY_A);

		if (isset($_POST['submit'])) {
			$express_type = isset($_POST['express_type']) ? $_POST['express_type'] : '';
			$express_no = isset($_POST['express_no']) ? $_POST['express_no'] : '';
			$error = '';
			$success = '';

			$udata = [
				'express_type' => $express_type,
				'express_no' => $express_no,
			];
			$wdata = [
				'id' => $id,
			];
			$uformat = [
				'%s',
				'%s',
			];
			$wformat = [
				'%d'
			];
			$wpdb->update($table_score_order, $udata, $wdata, $uformat, $wformat);

			$success = '修改成功';
		}
?>
		<div class="wrap">
			<h1>设置快递信息</h1>
			<?php if (isset($error) && $error) { ?><div class="notice notice-error">
					<ul>
						<li><?php echo $error; ?></li>
					</ul>
				</div><?php } ?>
			<?php if (isset($success) && $success) { ?>
				<div class="notice notice-success">
					<ul>
						<li><?php echo $success; ?></li>
					</ul>
				</div>
				<script>
					setTimeout(() => {
						window.location.href = '<?php echo add_query_arg(['page' => 'zhuige_scoremall_score_order'], 'admin.php'); ?>';
					}, 1000);
				</script>
			<?php } ?>
			<form method="post" enctype="multipart/form-data">
				<table class="form-table">
				<tr>
						<th scope="row">
							<label for="category">商品</label>
						</th>
						<td>
							<?php
								echo $order['goods_name'];
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="category">积分</label>
						</th>
						<td>
							<?php
								echo $order['goods_price'] . '积分';
							?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="category">收件人</label>
						</th>
						<td>
						<?php
								echo $order['addressee'] . '<br/>';
								echo $order['mobile'] . '<br/>';
								echo $order['address'] . '<br/>';
							?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="express_type">快递类型</label></th>
						<td><input type="text" class="regular-text" name="express_type" id="express_type" value="<?php echo $order['express_type']; ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="express_no">快递单号</label></th>
						<td><input type="text" class="regular-text" name="express_no" id="express_no" value="<?php echo $order['express_no']; ?>"></td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="修改">
				</p>
			</form>
		</div>
<?php
	} else {
		// Create an instance of our package class.
		$score_order_list = new ZhuiGe_ScoreMall_Score_Order_List();

		// Fetch, prepare, sort, and filter our data.
		$score_order_list->prepare_items();
?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
			<form id="movies-filter" method="get">
				<!-- For plugins, we also need to ensure that the form posts back to our current page -->
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<!-- Now we can render the completed list table -->
				<?php $score_order_list->display() ?>
			</form>
		</div>
<?php
	}

	
}
