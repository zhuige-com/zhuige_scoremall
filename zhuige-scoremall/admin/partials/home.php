<?php

/*
 * 追格积分商城Free
 * Author: 追格
 * Help document: https://www.zhuige.com/owfree/7685.html
 * github: https://github.com/longwenjunjie/zhuige_scoremall
 * gitee: https://gitee.com/longwenjunj/zhuige_scoremall
 * License：GPL-2.0
 * Copyright © 2022 www.zhuige.com All rights reserved.
 */

// 首页设置
CSF::createSection($prefix, array(
    'id'    => 'home',
    'title' => '首页设置',
    'icon'  => 'fas fa-home',
    'fields' => array(

        array(
            'id'      => 'home_thumb',
            'type'    => 'media',
            'title'   => '分享缩略图',
            'library' => 'image',
        ),

        array(
            'id'     => 'home_nav',
            'type'   => 'group',
            'title'  => '导航项',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
                array(
                    'id'      => 'image',
                    'type'    => 'media',
                    'title'   => '图片',
                    'library' => 'image',
                ),
                array(
                    'id'       => 'link',
                    'type'     => 'text',
                    'title'    => '链接',
                    'default'  => 'https://www.zhuige.com',
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
            'id'     => 'home_event',
            'type'   => 'fieldset',
            'title'  => '活动区域',
            'fields' => array(
                array(
                    'id'          => 'title',
                    'type'        => 'text',
                    'title'       => '标题',
                    'placeholder' => '标题'
                ),
        
                array(
                    'id'          => 'sub_title',
                    'type'        => 'text',
                    'title'       => '副标题',
                    'placeholder' => '副标题'
                ),
                
                array(
                    'id'         => 'event',
                    'type'       => 'accordion',
                    'title'      => '图片',
                    'accordions' => array(
        
                        array(
                            'title'  => '左图',
                            'fields' => array(
                                array(
                                    'id'          => 'l_title',
                                    'type'        => 'text',
                                    'title'       => '标题',
                                    'placeholder' => '标题'
                                ),
                                array(
                                    'id'      => 'l_image',
                                    'type'    => 'media',
                                    'title'   => '图片',
                                    'library' => 'image',
                                ),
                                array(
                                    'id'       => 'l_url',
                                    'type'     => 'text',
                                    'title'    => '链接',
                                    'default'  => 'https://www.zhuige.com',
                                ),
                            )
                        ),
        
                        array(
                            'title'  => '右上-左',
                            'fields' => array(
                                array(
                                    'id'          => 'rt_title',
                                    'type'        => 'text',
                                    'title'       => '标题',
                                    'placeholder' => '标题'
                                ),
                                array(
                                    'id'      => 'rt_image',
                                    'type'    => 'media',
                                    'title'   => '图片',
                                    'library' => 'image',
                                ),
                                array(
                                    'id'       => 'rt_url',
                                    'type'     => 'text',
                                    'title'    => '链接',
                                    'default'  => 'https://www.zhuige.com',
                                ),
                            )
                        ),
        
                        array(
                            'title'  => '右下',
                            'fields' => array(
                                array(
                                    'id'          => 'rd_title',
                                    'type'        => 'text',
                                    'title'       => '标题',
                                    'placeholder' => '标题'
                                ),
                                array(
                                    'id'      => 'rd_image',
                                    'type'    => 'media',
                                    'title'   => '图片',
                                    'library' => 'image',
                                ),
                                array(
                                    'id'       => 'rd_url',
                                    'type'     => 'text',
                                    'title'    => '链接',
                                    'default'  => 'https://www.zhuige.com',
                                ),
                            )
                        ),
        
                    )
                ),
                array(
                    'id'    => 'switch',
                    'type'  => 'switcher',
                    'title' => '开启/停用',
                    'subtitle' => '是否显示活动区域',
                    'default' => '1'
                ),
            ),
        ),

    )
));
