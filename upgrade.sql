-- 1.0 老用户升级 需执行以下SQL
ALTER TABLE `wp_zhuige_scoremall_score_order` ADD `trade_no` VARCHAR(50) NOT NULL COMMENT '订单号' AFTER `id`;