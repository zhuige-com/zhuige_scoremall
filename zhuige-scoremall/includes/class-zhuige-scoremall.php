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

class ZhuiGe_ScoreMall
{

	//分页 每页数量
	const POSTS_PER_PAGE = 10;

	protected $loader;

	protected $zhuige_scoremall;

	protected $version;

	public $admin;
	public $public;
	public $main;

	/**
	 * 获取配置
	 */
	public static function option_value($key, $default = '')
	{
		static $options = false;
		if (!$options) {
			$options = get_option('zhuige-scoremall');
		}

		if (isset($options[$key]) && !empty($options[$key])) {
			return $options[$key];
		}

		return $default;
	}

	/**
	 * 图片配置项url
	 */
	public static function option_image_url($image, $default = '')
	{
		if ($image && isset($image['url']) && $image['url']) {
			return $image['url'];
		} else {
			if ($default) {
				return plugins_url('public/images/' . $default, dirname(__FILE__));
			} else {
				return $default;
			}
		}
	}

	/**
	 * 用户头像
	 */
	public static function user_avatar($user_id)
	{
		$avatar = get_user_meta($user_id, 'zhuige_avatar', true);
		if (empty($avatar)) {
			$avatar = ZHUIGE_SCOREMALL_BASE_URL . 'public/images/default_avatar.jpg';
		}
		return $avatar;
	}

	/**
	 * 积分商品属性
	 */
	public static function post_goods_property($post_id, $key, $default = '')
	{
		$options = get_post_meta($post_id, 'zhuige-jq_goods-opt', true);
		if (isset($options[$key]) && !empty($options[$key])) {
			return $options[$key];
		}

		return $default;
	}

	/**
	 * 微信 token
	 */
	public static function get_wx_token()
	{
		$access_token = get_option('zhuige-scoremall-wx-access-token');
		if ($access_token && isset($access_token['expires_in']) && $access_token['expires_in'] > time()) {
			return $access_token;
		}

		$wechat = ZhuiGe_ScoreMall::option_value('basic_wechat');
		$app_id = '';
		$app_secret = '';
		if ($wechat) {
			$app_id = $wechat['appid'];
			$app_secret = $wechat['secret'];
		}

		if (empty($app_id) || empty($app_secret)) {
			return false;
		}

		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$app_id&secret=$app_secret";
		$body = wp_remote_get($url);
		if (!is_array($body) || is_wp_error($body) || $body['response']['code'] != '200') {
			return false;
		}
		$access_token = json_decode($body['body'], TRUE);

		$access_token['expires_in'] = $access_token['expires_in'] + time() - 200;
		update_option('zhuige-scoremall-wx-access-token', $access_token);

		return $access_token;
	}

	/**
	 * QQ token
	 */
	public static function get_qq_token()
	{
		$access_token = get_option('zhuige-scoremall-qq-access-token');
		if ($access_token && isset($access_token['expires_in']) && $access_token['expires_in'] > time()) {
			return $access_token;
		}

		$qq = ZhuiGe_ScoreMall::option_value('basic_qq');
		$app_id = '';
		$app_secret = '';
		if ($qq) {
			$app_id = $qq['appid'];
			$app_secret = $qq['secret'];
		}

		if (empty($app_id) || empty($app_secret)) {
			return false;
		}

		$url = "https://api.q.qq.com/api/getToken?grant_type=client_credential&appid=$app_id&secret=$app_secret";
		$body = wp_remote_get($url);
		if (!is_array($body) || is_wp_error($body) || $body['response']['code'] != '200') {
			return false;
		}
		$access_token = json_decode($body['body'], TRUE);

		$access_token['expires_in'] = $access_token['expires_in'] + time() - 200;
		update_option('zhuige-scoremall-qq-access-token', $access_token);

		return $access_token;
	}

	/**
	 * 百度 token
	 */
	public static function get_bd_token()
	{
		$access_token = get_option('zhuige-scoremall-bd-access-token');
		if ($access_token && isset($access_token['expires_in']) && $access_token['expires_in'] > time()) {
			return $access_token;
		}

		$baidu = ZhuiGe_ScoreMall::option_value('basic_baidu');
		$app_id = '';
		$app_secret = '';
		if ($baidu) {
			$app_id = $baidu['appid'];
			$app_secret = $baidu['secret'];
		}

		if (empty($app_id) || empty($app_secret)) {
			return false;
		}

		$url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=$app_id&client_secret=$app_secret&scope=smartapp_snsapi_base
		";
		$body = wp_remote_get($url);
		if (!is_array($body) || is_wp_error($body) || $body['response']['code'] != '200') {
			return false;
		}
		$access_token = json_decode($body['body'], TRUE);

		$access_token['expires_in'] = $access_token['expires_in'] + time() - 200;
		update_option('zhuige-scoremall-bd-access-token', $access_token);

		return $access_token;
	}

	public function __construct()
	{
		$this->zhuige_scoremall = 'zhuige-scoremall';
		$this->version = ZHUIGE_SCOREMALL_VERSION;

		$this->main = $this;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies()
	{
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'includes/class-zhuige-scoremall-loader.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'includes/class-zhuige-scoremall-i18n.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'includes/class-zhuige-scoremall-post_types.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'admin/class-zhuige-scoremall-admin.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'public/class-zhuige-scoremall-public.php';

		/**
		 * rest api
		 */
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'public/rest/class-zhuige-scoremall-base-controller.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'public/rest/class-zhuige-scoremall-setting-controller.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'public/rest/class-zhuige-scoremall-post-controller.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'public/rest/class-zhuige-scoremall-user-controller.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'public/rest/class-zhuige-scoremall-goods-controller.php';
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'public/rest/class-zhuige-scoremall-other-controller.php';

		/**
		 * AJAX
		 */
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'includes/class-zhuige-scoremall-ajax.php';

		/**
		 * 后台管理
		 */
		require_once ZHUIGE_SCOREMALL_BASE_DIR . 'admin/codestar-framework/codestar-framework.php';

		$this->loader = new ZhuiGe_ScoreMall_Loader();
	}

	private function set_locale()
	{
		$plugin_i18n = new ZhuiGe_ScoreMall_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function define_admin_hooks()
	{
		if (!is_admin()) {
			return;
		}

		$this->admin = new ZhuiGe_ScoreMall_Admin($this->get_zhuige_scoremall(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $this->admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $this->admin, 'enqueue_scripts');

		$zhuige_scoremall_post_types = new ZhuiGe_ScoreMall_Post_Types();
		$this->loader->add_action('init', $zhuige_scoremall_post_types, 'create_custom_post_type', 999);

		$this->loader->add_action('init', $this->admin, 'create_menu', 0);
		$this->loader->add_action('admin_init', $this->admin, 'admin_init');
		$this->loader->add_action('admin_menu', $this->admin, 'admin_menu', 20);
	}

	private function define_public_hooks()
	{
		$this->public = new ZhuiGe_ScoreMall_Public($this->get_zhuige_scoremall(), $this->get_version());

		$this->loader->add_action('init', $this->public, 'plugin_init');

		$this->loader->add_action('wp_enqueue_scripts', $this->public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $this->public, 'enqueue_scripts');

		$controller = [
			new ZhuiGe_ScoreMall_Setting_Controller(),
			new ZhuiGe_ScoreMall_Post_Controller(),
			new ZhuiGe_ScoreMall_User_Controller(),
			new ZhuiGe_ScoreMall_Goods_Controller(),
			new ZhuiGe_ScoreMall_Other_Controller(),
		];
		foreach ($controller as $control) {
			$this->loader->add_action('rest_api_init', $control, 'register_routes');
		}
	}

	public function run()
	{
		$this->loader->run();
	}

	public function get_zhuige_scoremall()
	{
		return $this->zhuige_scoremall;
	}

	public function get_loader()
	{
		return $this->loader;
	}

	public function get_version()
	{
		return $this->version;
	}
}
