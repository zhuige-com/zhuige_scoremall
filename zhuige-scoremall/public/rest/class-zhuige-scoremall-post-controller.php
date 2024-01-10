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

class ZhuiGe_ScoreMall_Post_Controller extends ZhuiGe_ScoreMall_Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->module = 'post';
		$this->routes = [
			'page' => 'get_page',
		];
	}

	/**
	 * 获取页面详情
	 */
	public function get_page($request)
	{
		$page_id = (int)($this->param_value($request, 'page_id'));
		if (!$page_id) {
			return $this->make_error('缺少参数');
		}

		global $wpdb;
		$table_post = $wpdb->prefix . 'posts';
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT post_title, post_content FROM `$table_post` WHERE ID=%d",
				$page_id
			)
		);
		if (!$result) {
			return $this->make_error('未找到文章');
		}
		$page['title'] = $result->post_title;
		$page['content'] = apply_filters('the_content', $result->post_content);

		return $this->make_success($page);
	}
}
