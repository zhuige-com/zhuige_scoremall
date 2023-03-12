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

class ZhuiGe_ScoreMall_Activator
{
    public static function activate()
    {
        global $wpdb;

        $charset_collate = '';
        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
        
		//积分账单
        /**
         * action 
         * --admin 后台修改
         * --exchange 积分兑换
         */
        $table_score_bills = $wpdb->prefix . 'zhuige_scoremall_score_bills';
        $sql = "CREATE TABLE IF NOT EXISTS `$table_score_bills` (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `action` varchar(32) NOT NULL DEFAULT '' COMMENT '任务类型',
            `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '额外信息',
            `user_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '用户ID',
            `score` int(11) NOT NULL COMMENT '分数',
            `time` int(10) UNSIGNED NOT NULL COMMENT '时间',
            PRIMARY KEY (`id`)
        ) $charset_collate;";
        dbDelta($sql);
        
        //积分兑换订单
        $table_score_order = $wpdb->prefix . 'zhuige_scoremall_score_order';
        $sql = "CREATE TABLE IF NOT EXISTS `$table_score_order` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
            `trade_no` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
            `user_id` bigint(20) NOT NULL COMMENT '用户',
            `goods_id` bigint(20) UNSIGNED NOT NULL COMMENT '商品ID',
            `goods_image` varchar(255) NOT NULL COMMENT '商品图片',
            `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
            `goods_price` int(10) UNSIGNED NOT NULL COMMENT '商品价格',
            `addressee` varchar(50) NOT NULL DEFAULT '' COMMENT '收件人',
            `mobile` varchar(20) NOT NULL COMMENT '手机',
            `address` varchar(100) NOT NULL DEFAULT '' COMMENT '地址',
            `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
            `express_type` varchar(50) NOT NULL DEFAULT '' COMMENT '快递类型',
            `express_no` varchar(50) NOT NULL DEFAULT '' COMMENT '快递单号',
            `createtime` int(10) UNSIGNED DEFAULT NULL COMMENT '创建时间',
            PRIMARY KEY (`id`)
        ) $charset_collate;";
        dbDelta($sql);
    }
}
