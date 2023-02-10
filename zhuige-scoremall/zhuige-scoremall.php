<?php

/**
 * Plugin Name:		追格积分商城Free
 * Plugin URI:		https://www.zhuige.com/product/jf.html
 * Description:		让Wordpress快速变身积分商城小程序。
 * Version:			1.2.2
 * Author:			追格
 * Author URI:		https://www.zhuige.com/
 * License:			GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:		zhuige-scoremall
 */

if (!defined('WPINC')) {
	die;
}

define('ZHUIGE_SCOREMALL_VERSION', '1.2.2');
define('ZHUIGE_SCOREMALL_BASE_DIR', plugin_dir_path(__FILE__));
define('ZHUIGE_SCOREMALL_BASE_NAME', plugin_basename(__FILE__));
define('ZHUIGE_SCOREMALL_BASE_URL', plugin_dir_url(__FILE__));

function activate_zhuige_scoremall()
{
	require_once ZHUIGE_SCOREMALL_BASE_DIR . 'includes/class-zhuige-scoremall-activator.php';
	ZhuiGe_ScoreMall_Activator::activate();
}

function deactivate_zhuige_scoremall()
{
	require_once ZHUIGE_SCOREMALL_BASE_DIR . 'includes/class-zhuige-scoremall-deactivator.php';
	ZhuiGe_ScoreMall_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_zhuige_scoremall');
register_deactivation_hook(__FILE__, 'deactivate_zhuige_scoremall');

function zhuige_scoremall_action_links($actions)
{
	$actions[] = '<a href="admin.php?page=zhuige-scoremall">设置</a>';
	$actions[] = '<a href="https://www.zhuige.com/page/docs.html" target="_blank">技术支持</a>';
    return $actions;
}
add_filter('plugin_action_links_' . ZHUIGE_SCOREMALL_BASE_NAME, 'zhuige_scoremall_action_links');

require ZHUIGE_SCOREMALL_BASE_DIR . 'includes/class-zhuige-scoremall.php';
require ZHUIGE_SCOREMALL_BASE_DIR . 'includes/zhuige-scoremall-function.php';
require ZHUIGE_SCOREMALL_BASE_DIR . 'includes/zhuige-scoremall-dashboard.php';
require ZHUIGE_SCOREMALL_BASE_DIR . 'includes/zhuige-scoremall-column.php';
require ZHUIGE_SCOREMALL_BASE_DIR . 'includes/zhuige-scoremall-property.php';
require ZHUIGE_SCOREMALL_BASE_DIR . 'includes/zhuige-scoremall-score-bills.php';
require ZHUIGE_SCOREMALL_BASE_DIR . 'includes/zhuige-scoremall-score-order.php';

function run_zhuige_scoremall()
{
	$plugin = new ZhuiGe_ScoreMall();
	$plugin->run();
}
run_zhuige_scoremall();
