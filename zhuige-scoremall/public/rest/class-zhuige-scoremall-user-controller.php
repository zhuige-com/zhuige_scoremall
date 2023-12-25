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

class ZhuiGe_ScoreMall_User_Controller extends ZhuiGe_ScoreMall_Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->module = 'user';
		$this->routes = [
			'login' => 'user_login',
			
			'logout' => 'user_logout',

			'score' => 'get_score',

			'set_info' => 'set_info',
		];
	}

	/**
	 *用户登录
	 */
	public function user_login($request)
	{
		$code = $this->param_value($request, 'code', '');
		$nickname = $this->param_value($request, 'nickname', '');
		$channel = $this->param_value($request, 'channel', '');

		if (empty($code) || empty($nickname) || empty($channel)) {
			return $this->make_error('缺少参数');
		}

		$session = false;
		if ('weixin' == $channel) {
			$session = $this->wx_code2openid($code);
		} else if ('qq' == $channel) {
			$session = $this->qq_code2openid($code);
		} else if ('baidu' == $channel) {
			$session = $this->bd_code2openid($code);
		}

		if (!is_array($session)) {
			return $this->make_error($session);
		}

		$user = get_user_by('login', $session['openid']);
		$first = 0;
		if ($user) {
			$user_id = $user->ID;
			$nickname = get_user_meta($user_id, 'nickname', true);
		} else {
			$email_domain = '@zhuige.com';
			$user_id = wp_insert_user([
				'user_login' => $session['openid'],
				'nickname' => $nickname,
				'user_nicename' => $nickname,
				'display_name' => $nickname,
				'user_email' => $session['openid'] . $email_domain,
				'role' => 'subscriber',
				'user_pass' => wp_generate_password(16, false),
			]);

			if (is_wp_error($user_id)) {
				return $this->make_error('创建用户失败');
			}

			$first = 1;
		}

		update_user_meta($user_id, 'jq_channel', $channel);

		if ('weixin' == $channel) {
			update_user_meta($user_id, 'jq_wx_session_key', $session['session_key']);
		}

		if (isset($session['unionid']) && !empty($session['unionid'])) {
			update_user_meta($user_id, 'jq_unionid', $session['unionid']);
		}

		$zhuige_token = $this->_generate_token();
		update_user_meta($user_id, 'zhuige_token', $zhuige_token);

		$user = array(
			'user_id' => $user_id,
			'nickname' => $nickname,
			'avatar' => ZhuiGe_ScoreMall::user_avatar($user_id),
			'token' => $zhuige_token,
		);

		if ($first) {
			$user['first'] = $first;
		}

		return $this->make_success($user);
	}

	/**
	 *用户注销
	 */
	public function user_logout($request)
	{
		$user_id = get_current_user_id();
		if (!$user_id) {
			return $this->make_error('还没有登陆', -1);
		}

		$res = wp_delete_user($user_id);
		if (!$res) {
			return $this->make_error('请稍后再试~');
		}

		global $wpdb;

		$wpdb->delete($wpdb->prefix . 'comments', ['user_id' => $user_id]);

		$wpdb->delete($wpdb->prefix . 'zhuige_scoremall_score_bills', ['user_id' => $user_id]);

		$wpdb->delete($wpdb->prefix . 'zhuige_scoremall_score_order', ['user_id' => $user_id]);

		return $this->make_success();
	}

	/**
	 * 获取当前用户积分
	 */
	public function get_score($request)
	{
		$my_user_id = get_current_user_id();

		//我的积分
		$my_score = 0;
		if ($my_user_id) {
			$my_score = (int)(get_user_meta($my_user_id, 'zhuige_score', true));
		}
		$data['my_score'] = $my_score;

		return $this->make_success($data);
	}

	/**
	 * 设置用户信息
	 */
	public function set_info($request)
	{
		$user_id = get_current_user_id();
		if (!$user_id) {
			return $this->make_error('还没有登陆', -1);
		}

		$avatar = $this->param_value($request, 'avatar', '');
		$nickname = $this->param_value($request, 'nickname', '');
		if (!$this->msg_sec_check($nickname)) {
			return $this->make_error('请勿发布敏感信息');
		}

		if (empty($nickname)) {
			return $this->make_error('昵称不可为空');
		}
		wp_update_user([
			'ID' => $user_id,
			'nickname' => $nickname,
			'user_nicename' => $nickname,
			'display_name' => $nickname,
		]);

		if (!empty($avatar)) {
			update_user_meta($user_id, 'zhuige_avatar', $avatar);
		}

		return $this->make_success();
	}

	/**
	 * 微信登录
	 */
	private function wx_code2openid($code)
	{
		$wechat = ZhuiGe_ScoreMall::option_value('basic_wechat');
		$app_id = '';
		$app_secret = '';
		if ($wechat) {
			$app_id = $wechat['appid'];
			$app_secret = $wechat['secret'];
		}

		if (empty($app_id) || empty($app_secret)) {
			return '请在后台设置微信appid和secret';
		}

		$params = [
			'appid' => $app_id,
			'secret' => $app_secret,
			'js_code' => $code,
			'grant_type' => 'authorization_code'
		];

		$result = wp_remote_get(add_query_arg($params, 'https://api.weixin.qq.com/sns/jscode2session'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return '网络请求异常';
		}

		// file_put_contents('wx_login', json_encode($result));

		$body = stripslashes($result['body']);
		$session = json_decode($body, true);

		if (!isset($session['openid']) || empty($session['openid'])) {
			return json_encode($session);
		}

		return $session;
	}

	/**
	 * QQ登录
	 */
	private function qq_code2openid($code)
	{
		$qq = ZhuiGe_ScoreMall::option_value('basic_qq');
		$app_id = '';
		$app_secret = '';
		if ($qq) {
			$app_id = $qq['appid'];
			$app_secret = $qq['secret'];
		}

		if (empty($app_id) || empty($app_secret)) {
			return '请在后台设置QQ appid和secret';
		}

		$params = [
			'appid' => $app_id,
			'secret' => $app_secret,
			'js_code' => $code,
			'grant_type' => 'authorization_code'
		];

		$result = wp_remote_get(add_query_arg($params, 'https://api.q.qq.com/sns/jscode2session'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return '网络请求异常';
		}

		// file_put_contents('qq_login', json_encode($result));

		$body = stripslashes($result['body']);
		$session = json_decode($body, true);

		if (!isset($session['openid']) || empty($session['openid'])) {
			return json_encode($session);
		}

		return $session;
	}

	/**
	 * 百度登录
	 */
	private function bd_code2openid($code)
	{
		$baidu = ZhuiGe_ScoreMall::option_value('basic_baidu');
		$app_id = '';
		$app_secret = '';
		if ($baidu) {
			$app_id = $baidu['appid'];
			$app_secret = $baidu['secret'];
		}

		if (empty($app_id) || empty($app_secret)) {
			return '请在后台设置百度appid和secret';
		}

		$params = [
			'client_id' => $app_id,
			'sk' => $app_secret,
			'code' => $code,
		];

		$result = wp_remote_get(add_query_arg($params, 'https://spapi.baidu.com/oauth/jscode2sessionkey'));
		if (!is_array($result) || is_wp_error($result) || $result['response']['code'] != '200') {
			return '网络请求异常';
		}

		// file_put_contents('bd_login', json_encode($result));

		$body = stripslashes($result['body']);
		$session = json_decode($body, true);

		if (!isset($session['openid']) || empty($session['openid'])) {
			return json_encode($session);
		}

		return $session;
	}

	private function _generate_token()
	{
		return md5(uniqid());
	}
}
