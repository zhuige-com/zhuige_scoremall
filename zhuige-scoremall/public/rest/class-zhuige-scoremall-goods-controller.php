<?php

/*
 * 追格积分商城Free
 * Author: 追格
 * Help document: https://www.zhuige.com/owfree/7685.html
 * github: https://github.com/zhuige-com/zhuige_scoremall
 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
 * License：GPL-2.0
 * Copyright © 2022-2023 www.zhuige.com All rights reserved.
 */

class ZhuiGe_ScoreMall_Goods_Controller extends ZhuiGe_ScoreMall_Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->module = 'goods';
		$this->routes = [
			'last' => 'get_last',
			'detail' => 'get_detail',

			'pre_exchange' => 'pre_exchange',
			'exchange' => 'exchange',

			'record' => 'record',
		];
	}

	/**
	 * 按【时间倒序】获取文章列表
	 */
	public function get_last($request)
	{
		$offset = (int)($this->param_value($request, 'offset', 0));

		$args = [
			'post_type' => ['jq_goods'],
			'posts_per_page' => ZhuiGe_ScoreMall::POSTS_PER_PAGE,
			'offset' => $offset,
			'orderby' => 'date',
		];

		$list = [];

		$query = new WP_Query();
		$result = $query->query($args);
		foreach ($result as $post) {
			$list[] = $this->_formatPost($post);
		}

		return $this->make_success([
			'list' => $list, 
			'more' => (count($result) >= ZhuiGe_ScoreMall::POSTS_PER_PAGE ? 'more' : 'nomore')
		]);
	}

	/**
	 * 获取文章详情
	 */
	public function get_detail($request)
	{
		$post_id = (int)($this->param_value($request, 'post_id'));
		if (!$post_id) {
			return $this->make_error('缺少参数');
		}
		
		$postObj = get_post($post_id);
		if (!$postObj) {
			return $this->make_error('商品不存在~');
		}

		$post = [
			'id' => $postObj->ID,
			'title' => $postObj->post_title,
		];

		$post['slide'] = ZhuiGe_ScoreMall::post_goods_property($postObj->ID, 'slide', []);
		$post['price'] = ZhuiGe_ScoreMall::post_goods_property($postObj->ID, 'price', 0);
		$post['quantity'] = ZhuiGe_ScoreMall::post_goods_property($postObj->ID, 'quantity', 0);

		//文章摘要
		$post["excerpt"] = $this->_getExcerpt($postObj);

		$post['content'] = apply_filters('the_content', $postObj->post_content);


		return $this->make_success($post);
	}

	/**
	 * 兑换预处理
	 */
	public function pre_exchange($request)
	{
		$post_id = (int)($this->param_value($request, 'post_id'));
		if (!$post_id) {
			return $this->make_error('缺少参数');
		}

		$postObj = get_post($post_id);

		$post = [
			'id' => $postObj->ID,
			'title' => $postObj->post_title,
		];

		$post['price'] = ZhuiGe_ScoreMall::post_goods_property($postObj->ID, 'price', 0);
		$post['thumbnail'] = $this->get_one_post_thumbnail($postObj, true);

		return $this->make_success($post);
	}

	/**
	 * 积分兑换
	 */
	public function exchange($request)
	{
		$my_user_id = get_current_user_id();
		if (!$my_user_id) {
			return $this->make_error('还没有登陆', -1);
		}

		$post_id = (int)($this->param_value($request, 'post_id'));
		if (!$post_id) {
			return $this->make_error('缺少参数');
		}

		$addressee = $this->param_value($request, 'addressee');
		if (empty($addressee)) {
			return $this->make_error('收件人不可为空');
		}

		$mobile = $this->param_value($request, 'mobile');
		if (empty($mobile)) {
			return $this->make_error('手机号不可为空');
		}

		$address = $this->param_value($request, 'address');
		if (empty($address)) {
			return $this->make_error('地址不可为空');
		}

		$remark = $this->param_value($request, 'remark');

		if (!$this->msg_sec_check($addressee . $mobile . $address . $remark)) {
			return $this->make_error('请勿输入敏感信息');
		}

		$stock = (int)(ZhuiGe_ScoreMall::post_goods_property($post_id, 'stock', 0));
		if ($stock < 1) {
			return $this->make_error('库存不足');
		}

		$my_score = (int)(get_user_meta($my_user_id, 'zhuige_score', true));
		$goods_price = (int)(ZhuiGe_ScoreMall::post_goods_property($post_id, 'price', 0));
		if ($my_score < $goods_price) {
			return $this->make_error('积分不够');
		}
		update_user_meta($my_user_id, 'zhuige_score', $my_score - $goods_price);

		$postObj = get_post($post_id);
		$goods_image = $this->get_one_post_thumbnail($postObj, true);
		$goods_name = $postObj->post_title;

		global $wpdb;
		$trade_no = 'ZG_' . $my_user_id . '_' . time();
		$wpdb->insert("{$wpdb->prefix}zhuige_scoremall_score_order", [
			'trade_no' => $trade_no,
			'user_id' => $my_user_id,
			'goods_id' => $post_id,
			'goods_image' => $goods_image,
			'goods_name' => $goods_name,
			'goods_price' => $goods_price,
			'addressee' => $addressee,
			'mobile' => $mobile,
			'address' => $address,
			'remark' => $remark,
			'createtime' => time()
		]);

		$order_id = $wpdb->insert_id;

		$wpdb->insert("{$wpdb->prefix}zhuige_scoremall_score_bills", [
			'user_id' => $my_user_id,
			'action' => 'exchange',
			'extra' => $order_id,
			'score' => -$goods_price,
			'time' => time()
		]);

		$options = get_post_meta($post_id, 'zhuige-jq_goods-opt', true);
		$options['quantity'] = (int)($options['quantity']) + 1;
		$options['stock'] = (int)($options['stock']) - 1;
		update_post_meta($post_id, 'zhuige-jq_goods-opt', $options);

		return $this->make_success('');
	}

	/**
	 * 兑换记录
	 */
	public function record($request)
	{
		$my_user_id = get_current_user_id();
		if (!$my_user_id) {
			return $this->make_error('还没有登陆', -1);
		}

		$offset = (int)($this->param_value($request, 'offset', 0));

		global $wpdb;
		$table_score_order = $wpdb->prefix . 'zhuige_scoremall_score_order';
		$orders = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM `$table_score_order` WHERE `user_id`=%d ORDER BY `id` DESC LIMIT %d, %d", $my_user_id, $offset, ZhuiGe_ScoreMall::POSTS_PER_PAGE
			),
		ARRAY_A);
		foreach ($orders as &$order) {
			$order['createtime'] = date('Y.m.d', $order['createtime']);
		}

		return $this->make_success(['more' => count($orders) == ZhuiGe_ScoreMall::POSTS_PER_PAGE ? 'more' : 'nomore', 'orders' => $orders]);
	}

	/**
	 * 获取摘要
	 */
	private function _getExcerpt($post)
	{
		if ($post->post_excerpt) {
			return html_entity_decode(wp_trim_words($post->post_excerpt, 50, '...'));
		} else {
			$content = apply_filters('the_content', $post->post_content);
			return html_entity_decode(wp_trim_words($content, 50, '...'));
		}
	}

	/**
	 * 格式化文章
	 */
	private function _formatPost($post)
	{
		$data = [
			'id' => $post->ID,
			'title' => $post->post_title,
			'excerpt' => $this->_getExcerpt($post)
		];

		//缩略图
		$data['thumbnail'] = $this->get_one_post_thumbnail($post, true);

		$data['price'] = ZhuiGe_ScoreMall::post_goods_property($post->ID, 'price', 0);
		$data['quantity'] = ZhuiGe_ScoreMall::post_goods_property($post->ID, 'quantity', 0);

		return $data;
	}
}
