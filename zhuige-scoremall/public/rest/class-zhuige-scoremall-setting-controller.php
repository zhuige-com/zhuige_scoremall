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

class ZhuiGe_ScoreMall_Setting_Controller extends ZhuiGe_ScoreMall_Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->module = 'setting';
		$this->routes = [
			'home' => 'get_home',
			'mine' => 'get_mine',
			'login' => 'get_login',
		];
	}

	/**
	 * 获取配置 首页
	 */
	public function get_home($request)
	{
		$data = [];

		//小程序名称
		$data['title'] = ZhuiGe_ScoreMall::option_value('basic_title', '积分商城');

		//描述
		$data['desc'] = ZhuiGe_ScoreMall::option_value('basic_desc', '');

		//关键字
		$data['keywords'] = ZhuiGe_ScoreMall::option_value('basic_keywords', '');

		$my_user_id = get_current_user_id();

		//我的积分
		$my_score = 0;
		if ($my_user_id) {
			$my_score = (int)(get_user_meta($my_user_id, 'zhuige_score', true));
		}
		$data['my_score'] = $my_score;

		// 幻灯片
		$slides_org = ZhuiGe_ScoreMall::option_value('home_slide');
		$slides = [];
		if (is_array($slides_org)) {
			foreach ($slides_org as $item) {
				if ($item['switch'] && $item['image'] && $item['image']['url']) {
					$slides[] = [
						'image' => $item['image']['url'],
						'link' => $item['link'],
					];
				}
			}
		}
		$data['slides'] = $slides;

		//图标导航
		$icon_nav_org = ZhuiGe_ScoreMall::option_value('home_nav');
		$icon_navs = [];
		if (is_array($icon_nav_org)) {
			foreach ($icon_nav_org as $item) {
				if ($item['switch'] && $item['image'] && $item['image']['url']) {
					$icon_navs[] = [
						'image' => $item['image']['url'],
						'link' => $item['link'],
						'title' => $item['title'],
					];
				}
			}
		}
		$data['icon_navs'] = $icon_navs;

		//精选
		$events = ZhuiGe_ScoreMall::option_value('home_event');
		if ($events && $events['switch']) {
			if ($events['event']['l_image']) {
				$events['event']['l_image'] = $events['event']['l_image']['url'];
			}
			if ($events['event']['rt_image']) {
				$events['event']['rt_image'] = $events['event']['rt_image']['url'];
			}
			if ($events['event']['rd_image']) {
				$events['event']['rd_image'] = $events['event']['rd_image']['url'];
			}
		} else {
			$events = '';
		}
		$data['events'] = $events;

		// 首页分享标题
		$data['home_title'] = ZhuiGe_ScoreMall::option_value('home_title', '');

		//首页分享头图
		$home_thumb = ZhuiGe_ScoreMall::option_value('home_thumb');
		if ($home_thumb && $home_thumb['url']) {
			$data['thumb'] = $home_thumb['url'];
		}

		return $this->make_success($data);
	}

	/**
	 * 获取配置 我的
	 */
	public function get_mine($request)
	{
		$data = [];

		$my_bg = ZhuiGe_ScoreMall::option_value('my_bg');
		$data['background'] = ZhuiGe_ScoreMall::option_image_url($my_bg, 'default_bg.jpg');

		$copyright = ZhuiGe_ScoreMall::option_value('copyright');
		if ($copyright['switch']) {
			$copyright['logo'] = ZhuiGe_ScoreMall::option_image_url($copyright['logo'], 'logo_f.png');
			$data['copyright'] = $copyright;
		}

		$my_about = ZhuiGe_ScoreMall::option_value('my_about');
		if ($my_about) {
			$data['page_about'] = '/pages/page/page?page_id=' . $my_about;
		}

		return $this->make_success($data);
	}

	/**
	 * 获取配置 登录
	 */
	public function get_login($request)
	{
		$data = [];

		$login_bg = ZhuiGe_ScoreMall::option_value('login_bg');
		$data['background'] = ZhuiGe_ScoreMall::option_image_url($login_bg, 'default_bg.jpg');

		$login_logo = ZhuiGe_ScoreMall::option_value('login_logo');
		$data['logo'] = ZhuiGe_ScoreMall::option_image_url($login_logo, 'logo_f.png');

		$data['title'] = ZhuiGe_ScoreMall::option_value('login_title');

		$login_yhxy = ZhuiGe_ScoreMall::option_value('login_yhxy');
		if ($login_yhxy) {
			$data['yhxy'] = '/pages/page/page?page_id=' . $login_yhxy;
		}

		$login_yszc = ZhuiGe_ScoreMall::option_value('login_yszc');
		if ($login_yszc) {
			$data['yszc'] = '/pages/page/page?page_id=' . $login_yszc;
		}

		$data['mobile'] = ZhuiGe_ScoreMall::option_value('login_require_mobile');

		return $this->make_success($data);
	}
}
