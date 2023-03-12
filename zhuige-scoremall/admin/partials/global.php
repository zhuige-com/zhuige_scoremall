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

// 基础设置
CSF::createSection($prefix, array(
    'id'    => 'basic',
    'title' => '基础设置',
    'icon'  => 'fas fa-cubes',
    'fields' => array(

        array(
            'id'          => 'basic_title',
            'type'        => 'text',
            'title'       => '标题',
            'placeholder' => '标题'
        ),

        array(
            'id'          => 'basic_keywords',
            'type'        => 'text',
            'title'       => '关键字',
            'subtitle'    => '仅百度小程序使用',
            'placeholder' => '关键字'
        ),

        array(
            'id'          => 'basic_desc',
            'type'        => 'text',
            'title'       => '描述',
            'placeholder' => '描述'
        ),

        array(
            'id'     => 'basic_wechat',
            'type'   => 'fieldset',
            'title'  => '微信小程序',
            'fields' => array(
                array(
                    'id'    => 'appid',
                    'type'  => 'text',
                    'title' => 'App ID',
                ),
                array(
                    'id'    => 'secret',
                    'type'  => 'text',
                    'title' => 'App Secret',
                ),
            ),
        ),

        array(
            'id'     => 'basic_baidu',
            'type'   => 'fieldset',
            'title'  => '百度小程序',
            'fields' => array(
                array(
                    'id'    => 'appid',
                    'type'  => 'text',
                    'title' => 'App Key',
                ),
                array(
                    'id'    => 'secret',
                    'type'  => 'text',
                    'title' => 'App Secret',
                ),
            ),
        ),

        array(
            'id'     => 'basic_qq',
            'type'   => 'fieldset',
            'title'  => 'QQ小程序',
            'fields' => array(
                array(
                    'id'    => 'appid',
                    'type'  => 'text',
                    'title' => 'App ID',
                ),
                array(
                    'id'    => 'secret',
                    'type'  => 'text',
                    'title' => 'App Secret',
                ),
            ),
        ),
        
    )
));
