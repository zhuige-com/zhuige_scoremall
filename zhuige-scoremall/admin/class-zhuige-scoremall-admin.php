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

class ZhuiGe_ScoreMall_Admin
{
	private $zhuige_scoremall;

	private $version;

	public function __construct($zhuige_scoremall, $version)
	{
		$this->zhuige_scoremall = $zhuige_scoremall;
		$this->version = $version;
	}

	public function enqueue_styles()
	{
		wp_enqueue_style($this->zhuige_scoremall, ZHUIGE_SCOREMALL_BASE_URL . 'admin/css/zhuige-scoremall-admin.css', array(), $this->version, 'all');
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script($this->zhuige_scoremall, ZHUIGE_SCOREMALL_BASE_URL . 'admin/js/zhuige-scoremall-admin.js', array('jquery'), $this->version, false);
	}

	public function create_menu()
	{
		$prefix = 'zhuige-scoremall';

		CSF::createOptions($prefix, array(
			'framework_title' => '追格积分商城Free <small>by <a href="https://www.zhuige.com" target="_blank" title="追格积分商城Free">www.zhuige.com</a></small>',
			'menu_title' => '追格积分商城Free',
			'menu_slug'  => 'zhuige-scoremall',
			'menu_position' => 2,
			'show_bar_menu' => false,
            'show_sub_menu' => false,
			'footer_credit' => 'Thank you for creating with <a href="https://www.zhuige.com/" target="_blank">追格</a>',
			'menu_icon' => 'dashicons-layout',
		));

		$base_dir = plugin_dir_path(__FILE__);
		$base_url = plugin_dir_url(__FILE__);
		require_once $base_dir . 'partials/overview.php';
		require_once $base_dir . 'partials/global.php';
		require_once $base_dir . 'partials/home.php';
		require_once $base_dir . 'partials/mine.php';
		require_once $base_dir . 'partials/login.php';

		//
        // 备份
        //
        CSF::createSection($prefix, array(
            'title'       => '备份',
            'icon'        => 'fas fa-shield-alt',
            'fields'      => array(
                array(
                    'type' => 'backup',
                ),
            )
        ));

		//积分商品属性
        $prefix_jq_goods_opts = 'zhuige-jq_goods-opt';

        CSF::createMetabox($prefix_jq_goods_opts, array(
            'title'        => '追格积分商城设置',
            'post_type'    => 'jq_goods',
        ));

        CSF::createSection($prefix_jq_goods_opts, array(
            'fields' => array(

                array(
                    'id'     => 'slide',
                    'type'   => 'group',
                    'title'  => '幻灯片',
                    'fields' => array(
                        array(
                            'id'      => 'image',
                            'type'    => 'media',
                            'title'   => '图片',
                            'library' => 'image',
                        ),
                    ),
                ),

                array(
                    'id'      => 'price',
                    'type'    => 'number',
                    'title'   => '价格',
                    'unit'    => '分',
                    'default' => '1',
                ),

				array(
                    'id'      => 'stock',
                    'type'    => 'number',
                    'title'   => '库存',
                    'unit'    => '套',
                    'default' => '100',
                ),

                array(
                    'id'      => 'quantity',
                    'type'    => 'number',
                    'title'   => '销量',
                    'unit'    => '套',
                    'default' => '0',
                ),

            )
        ));
	}

	public function admin_init()
	{
		$this->handle_external_redirects();
	}

	public function admin_menu()
	{
		add_submenu_page('zhuige-scoremall', '', '追格产品', 'manage_options', 'ZhuiGe_ScoreMall_setup', array(&$this, 'handle_external_redirects'));
		add_submenu_page('zhuige-scoremall', '', '新版下载', 'manage_options', 'ZhuiGe_ScoreMall_upgrade', array(&$this, 'handle_external_redirects'));
	}

	public function handle_external_redirects()
	{
		if (empty($_GET['page'])) {
			return;
		}

		if ('ZhuiGe_ScoreMall_setup' === $_GET['page']) {
			wp_redirect('https://www.zhuige.com/product.html');
			die;
		}

		if ('ZhuiGe_ScoreMall_upgrade' === $_GET['page']) {
			wp_redirect('https://www.zhuige.com/product/jf.html');
			die;
		}
	}
}
