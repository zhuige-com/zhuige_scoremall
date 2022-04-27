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

class ZhuiGe_ScoreMall_Score_Order_List extends WP_List_Table
{

	public function __construct()
	{
		parent::__construct(array(
			'singular' => '积分兑换订单',     // Singular name of the listed records.
			'plural'   => '积分兑换订单',    // Plural name of the listed records.
			'ajax'     => false,       // Does this table support ajax?
		));
	}

	public static function get_datas($per_page = 5, $page_number = 1, $search = null)
	{
		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}zhuige_scoremall_score_order WHERE 1=1";

		if ($search) {
			$sql .= " AND `trade_no` LIKE '%" . esc_sql($search) . "%'";
		}

		if (!empty($_REQUEST['orderby'])) {
			$sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
			$sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
		} else {
			$sql .= ' ORDER BY createtime DESC';
		}

		// $sql .= " LIMIT $per_page";
		// $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
		$sql .= $wpdb->prepare(" LIMIT %d OFFSET %d", $per_page, ($page_number - 1) * $per_page);

		$result = $wpdb->get_results($sql, 'ARRAY_A');

		return $result;
	}

	public function get_columns()
	{
		$columns = array(
			'cb'        => '<input type="checkbox" />', // Render a checkbox instead of text.
			'id'		    => 'ID',
			'trade_no'		=> '订单号',
			'user'	    	=> '用户',
			'goods'			=> '商品',
			'remark'		=> '备注',
			'addressee'		=> '收件人',
			'express'		=> '快递',
			'createtime'	=> '创建时间'
		);

		return $columns;
	}

	protected function get_sortable_columns()
	{
		$sortable_columns = array(
			'createtime'  => array('createtime', false),
		);

		return $sortable_columns;
	}

	protected function column_default($item, $column_name)
	{
		switch ($column_name) {
			case 'id':
			case 'trade_no':
			case 'remark':
				return $item[$column_name];
			default:
				return print_r($item, true); // Show the whole array for troubleshooting purposes.
		}
	}

	protected function column_cb($item)
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			'order_ids',  				// Let's simply repurpose the table's singular label ("movie").
			$item['id']                 // The value of the checkbox should be the record's ID.
		);
	}

	protected function column_user($item)
	{
		$avatar = ZhuiGe_ScoreMall::user_avatar($item['user_id']);
		$nickname = get_user_meta($item['user_id'], 'nickname', true);
		return "<img src='$avatar' style='width:48px;height:48px;'/><div>昵称：$nickname </div>";
	}

	protected function column_goods($item)
	{
		$value = "<img src='" . $item['goods_image'] . "' style='width:48px;height:48px;'/>";
		$value .= "<div>" . $item['goods_name'] . "</div>";
		$value .= "<div>" . $item['goods_price'] . "积分</div>";
		return $value;
	}

	protected function column_addressee($item)
	{
		$value = "<div>收件人：" . $item['addressee'] . "</div>";
		$value .= "<div>手机号：" . $item['mobile'] . "</div>";
		$value .= "<div>地址：" . $item['address'] . "</div>";
		return $value;
	}

	protected function column_express($item)
	{
		$value = "<div>" . $item['express_type'] . "</div>";
		$value .= "<div>快递单号：" . $item['express_no'] . "</div>";

		$page = wp_unslash($_REQUEST['page']); // WPCS: Input var ok.

		// Build edit row action.
		$edit_query_args = array(
			'page'   => $page,
			'action' => 'edit',
			'id'  => $item['id'],
		);

		$actions['edit'] = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url(wp_nonce_url(add_query_arg($edit_query_args, 'admin.php'), 'edit_' . $item['id'])),
			'编辑'
		);

		$value .= $this->row_actions($actions);

		return $value;
	}

	protected function column_createtime($item)
	{
		return wp_date("Y-m-d H:i:s", $item['createtime']);
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
		$action = isset($_GET['action']) ? sanitize_text_field(wp_unslash($_GET['action'])) : '';
		if ('bulk_delete' == $action) {
			if (isset($_GET['order_ids'])) {
				$order_ids = sanitize_text_field(wp_unslash($_GET['order_ids']));

				global $wpdb;
				foreach ($order_ids as $order_id) {
					$wpdb->delete("{$wpdb->prefix}zhuige_scoremall_score_order", ['id' => $order_id], ['%d']);
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

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}zhuige_scoremall_score_order WHERE 1=1";

		if ($search) {
			$sql .= " AND `trade_no` LIKE '%" . esc_sql($search) . "%'";
		}

		return $wpdb->get_var($sql);
	}

	/**
	 * 修改表格样式
	 */
	protected function get_table_classes() {
		$mode = get_user_setting( 'posts_list_mode', 'list' );

		$mode_class = esc_attr( 'table-view-' . $mode );

		return array( 'widefat', 'striped', $mode_class, $this->_args['plural'] );
	}
}
