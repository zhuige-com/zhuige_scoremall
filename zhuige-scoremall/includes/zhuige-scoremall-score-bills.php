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

require dirname( __FILE__ ) . '/class-zhuige-scoremall-score-bill-list.php';

add_action('admin_menu', 'zhuige_scoremall_add_score_bills_menu');
function zhuige_scoremall_add_score_bills_menu() {
	add_menu_page(
		'积分账单', 			// Page title.
		'积分账单',        		// Menu title.
		'activate_plugins',			// Capability.
		'zhuige_scoremall_score_bills',			// Menu slug.
		'zhuige_scoremall_render_score_bills',		// Callback function.
		'',
		3
	);
}

function zhuige_scoremall_render_score_bills() {
	// Create an instance of our package class.
	$score_bill_list = new ZhuiGe_ScoreMall_Score_Bill_List();

	// Fetch, prepare, sort, and filter our data.
	$score_bill_list->prepare_items();
?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		<form id="movies-filter" method="get">
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<!-- Now we can render the completed list table -->
			<?php $score_bill_list->display() ?>
		</form>
	</div>
<?php
	
}
