<?php

/*
 * 追格积分商城Free
 * Author: 追格
 * Help document: https://www.zhuige.com/owfree/7685.html
 * github: https://github.com/zhuige-com/zhuige_scoremall
 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
 * License：GPL-2.0
 * Copyright © 2022-2024 www.zhuige.com All rights reserved.
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
		'dashicons-awards',
		3
	);
}

function zhuige_scoremall_render_score_bills() {
	$score_bill_list = new ZhuiGe_ScoreMall_Score_Bill_List();
	$search = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
	$score_bill_list->prepare_items($search);
?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>

		<?php
		if (strlen($search)) {
			echo '<span class="subtitle">';
			printf(
				__('Search results for: %s'),
				'<strong>' . esc_html($search) . '</strong>'
			);
			echo '</span>';
		}
		?>
		<hr class="wp-header-end">

		<?php $score_bill_list->views(); ?>

		<form method="get">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<?php $score_bill_list->search_box('搜索', 'search_id'); ?>
		</form>

		<form id="movies-filter" method="get">
			<input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']) ?>" />
			<?php $score_bill_list->display() ?>
		</form>
	</div>
<?php
	
}
