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

class ZhuiGe_ScoreMall_Public
{

	private $zhuige_scoremall;

	private $version;

	public function __construct($zhuige_scoremall, $version)
	{
		$this->zhuige_scoremall = $zhuige_scoremall;
		$this->version = $version;
	}

	public function plugin_init()
	{
		$token = '';
		if (isset($_GET['token'])) {
			$token = sanitize_text_field(wp_unslash($_GET['token']));
		} else if (isset($_POST['token'])) {
			$token = sanitize_text_field(wp_unslash($_POST['token']));
		} else {
			$json = json_decode(file_get_contents('php://input'), TRUE);
			if ($json && isset($json['token'])) {
				$token = $json['token'];
			}
		}

		if (empty($token) || $token == 'false') {
			return;
		}

		global $wpdb;
		$table_usermeta = $wpdb->prefix . 'usermeta';
		$user_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT user_id FROM `$table_usermeta` WHERE meta_key='zhuige_token' AND meta_value='%s'",
				$token
			)
		);

		if ($user_id) {
			wp_set_current_user($user_id);
		}
	}

	public function enqueue_styles()
	{
		wp_enqueue_style($this->zhuige_scoremall, ZHUIGE_SCOREMALL_BASE_URL . 'public/css/zhuige-scoremall-public.css', array(), $this->version, 'all');
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script($this->zhuige_scoremall, ZHUIGE_SCOREMALL_BASE_URL . 'public/js/zhuige-scoremall-public.js', array('jquery'), $this->version, false);
	}
}
