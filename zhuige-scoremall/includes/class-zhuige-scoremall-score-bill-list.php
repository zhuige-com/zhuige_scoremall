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

class ZhuiGe_ScoreMall_Score_Bill_List extends WP_List_Table
{

	public function __construct()
	{
		parent::__construct(array(
			'singular' => '用户积分',     // Singular name of the listed records.
			'plural'   => '用户积分',    // Plural name of the listed records.
			'ajax'     => false,       // Does this table support ajax?
		));
	}

	public static function get_datas($per_page = 5, $page_number = 1, $search = null)
	{
		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}zhuige_scoremall_score_bills WHERE 1=1";

		if ($search) {
			$sql .= " AND `user_id`=$search";
		}

		if (!empty($_REQUEST['orderby'])) {
			$sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
			$sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
		} else {
			$sql .= ' ORDER BY time DESC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


		$result = $wpdb->get_results($sql, 'ARRAY_A');

		return $result;
	}

	public function get_columns()
	{
		$columns = array(
			'cb'        => '<input type="checkbox" />', // Render a checkbox instead of text.
			'avatar'	    => '头像',
			'nickname'		=> '昵称',
			'total'			=> '积分总计',
			'score'			=> '积分变化',
			'action'		=> '备注',
			'time'		    => '时间'
		);

		return $columns;
	}

	protected function get_sortable_columns()
	{
		$sortable_columns = array(
			'time'  		=> array('time', false),
		);

		return $sortable_columns;
	}

	protected function column_default($item, $column_name)
	{
		switch ($column_name) {
			case 'score':
				return $item[$column_name];
			default:
				return print_r($item, true); // Show the whole array for troubleshooting purposes.
		}
	}

	protected function column_cb($item)
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			'bill_ids',  				// Let's simply repurpose the table's singular label ("movie").
			$item['id']                 // The value of the checkbox should be the record's ID.
		);
	}

	protected function column_avatar($item)
	{
		$avatar = ZhuiGe_ScoreMall::user_avatar($item['user_id']);
		return  "<img src='$avatar' style='width:48px;height:48px;'/>";
	}

	protected function column_nickname($item)
	{
		return get_user_meta($item['user_id'], 'nickname', true) . '(' . $item['user_id'] . ')';
	}

	protected function column_total($item)
	{
		return get_user_meta($item['user_id'], 'zhuige_score', true);
	}

	protected function column_action($item)
	{
		if ($item['action'] == 'admin') {
			return '后台修改';
		} else if ($item['action'] == 'exchange') {
			return '积分兑换';
		}
	}

	protected function column_time($item)
	{
		return wp_date("Y-m-d H:i:s", $item['time']);
	}

	protected function get_bulk_actions()
	{
		$actions = array(
			'bulk_delete' => '删除',
		);

		return $actions;
	}

	protected function process_bulk_action()
	{
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		if ('bulk_delete' == $action) {
			if (isset($_GET['bill_ids'])) {
				$bill_ids = $_GET['bill_ids'];

				global $wpdb;
				foreach ($bill_ids as $bill_id) {
					$wpdb->delete("{$wpdb->prefix}zhuige_scoremall_score_bills", ['id' => $bill_id], ['%d']);
				}
			}

			$page = wp_unslash($_REQUEST['page']);
			$query = ['page' => $page];
			$redirect = add_query_arg($query, admin_url('admin.php'));
			echo '<script>window.location.href="' . esc_url($redirect) . '"</script>';
		}
	}

	function prepare_items($search = null)
	{
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->process_bulk_action();

		$per_page = 10;
		$current_page = $this->get_pagenum();
		$total_items  = $this->record_count($search);

		$this->items = $this->get_datas($per_page, $current_page, $search);

		$this->set_pagination_args(array(
			'total_items' => $total_items,                     // WE have to calculate the total number of items.
			'per_page'    => $per_page,                        // WE have to determine how many items to show on a page.
			'total_pages' => ceil($total_items / $per_page),   // WE have to calculate the total number of pages.
		));
	}

	/**
	 * Callback to allow sorting of example data.
	 *
	 * @param string $a First value.
	 * @param string $b Second value.
	 *
	 * @return int
	 */
	protected function usort_reorder($a, $b)
	{
		// If no sort, default to title.
		$orderby = !empty($_REQUEST['orderby']) ? wp_unslash($_REQUEST['orderby']) : 'title'; // WPCS: Input var ok.

		// If no order, default to asc.
		$order = !empty($_REQUEST['order']) ? wp_unslash($_REQUEST['order']) : 'asc'; // WPCS: Input var ok.

		// Determine sort order.
		$result = strcmp($a[$orderby], $b[$orderby]);

		return ('asc' === $order) ? $result : -$result;
	}

	public function record_count($search)
	{
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}zhuige_scoremall_score_bills WHERE 1=1";

		if ($search) {
			$sql .= " AND `user_id`=$search";
		}

		return $wpdb->get_var($sql);
	}
}
