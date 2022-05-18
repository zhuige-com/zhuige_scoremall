<?php

/*
 * 追格积分商城Free
 * Author: 追格
 * Help document: https://www.zhuige.com/owfree/7685.html
 * github: https://github.com/zhuige-com/zhuige_scoremall
 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
 * License：GPL-2.0
 * Copyright © 2022 www.zhuige.com All rights reserved.
 */

//
// 我的
//
CSF::createSection($prefix, array(
    'id' => 'my',
    'title' => '我的',
    'icon'  => 'fas fa-user-edit',
    'fields' => array(
        array(
            'id'      => 'my_bg',
            'type'    => 'media',
            'title'   => '顶部背景图',
            'library' => 'image',
        ),

        array(
            'id'     => 'copyright',
            'type'   => 'fieldset',
            'title'  => '版权信息',
            'fields' => array(
                array(
                    'id'      => 'logo',
                    'type'    => 'media',
                    'title'   => 'LOGO',
                    'library' => 'image',
                ),

                array(
                    'id'    => 'text',
                    'type'  => 'text',
                    'title' => '版权声明',
                ),
                array(
                    'id'    => 'switch',
                    'type'  => 'switcher',
                    'title' => '开启/停用',
                    'default' => '1'
                ),
            ),
        ),

        array(
            'id'          => 'my_about',
            'type'        => 'select',
            'title'       => '关于我们',
            'chosen'      => true,
            // 'multiple'    => true,
            'sortable'    => true,
            'ajax'        => true,
            'options'     => 'pages',
            'placeholder' => '选择一个页面',
        ),
    )
));
