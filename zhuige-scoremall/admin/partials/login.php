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

//
// 登录注销页
//
CSF::createSection($prefix, array(
    'id' => 'my',
    'title' => '登录注销',
    'icon'  => 'fas fa-user-lock',
    'fields' => array(
        array(
            'id'      => 'login_bg',
            'type'    => 'media',
            'title'   => '顶部背景图',
            'library' => 'image',
        ),

        array(
            'id'      => 'login_logo',
            'type'    => 'media',
            'title'   => '登录页LOGO',
            'library' => 'image',
        ),

        array(
            'id'    => 'login_title',
            'type'  => 'text',
            'title' => '标题',
        ),

        array(
            'id'          => 'login_yhxy',
            'type'        => 'select',
            'title'       => '用户协议',
            'chosen'      => true,
            // 'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'options'     => 'pages',
            'placeholder' => '选择一个页面',
        ),

        array(
            'id'          => 'login_yszc',
            'type'        => 'select',
            'title'       => '隐私政策',
            'chosen'      => true,
            // 'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'options'     => 'pages',
            'placeholder' => '选择一个页面',
        ),

        array(
            'id'    => 'logout_explain',
            'type'  => 'wp_editor',
            'title' => '注销说明',
        ),
    )
));
