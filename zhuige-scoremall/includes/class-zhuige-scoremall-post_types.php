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

class ZhuiGe_ScoreMall_Post_Types
{
    public function assign_capabilities($caps_map, $users)
    {
        foreach ($users as $user) {
            $user_role = get_role($user);
            foreach ($caps_map as $cap_map_key => $capability) {
                $user_role->add_cap($capability);
            }
        }
    }

    public function create_custom_post_type()
    {
        $goods_labels = array(
            'name'               => '追格积分商品',
            'singular_name'      => '追格积分商品', 'post type 单个 item 时的名称，因为英文有复数',
            'add_new'            => '新建积分商品', '添加新内容的链接名称',
            'add_new_item'       => '新建一个积分商品',
            'edit_item'          => '编辑积分商品',
            'new_item'           => '新积分商品',
            'all_items'          => '所有积分商品',
            'view_item'          => '查看积分商品',
            'search_items'       => '搜索积分商品',
            'not_found'          => '没有找到有关积分商品',
            'not_found_in_trash' => '回收站里面没有相关积分商品',
            'parent_item_colon'  => '',
            'menu_name'          => '追格积分商品'
        );
        $goods_args = array(
            'labels'        => $goods_labels,
            'description'   => '我们网站的积分商品信息',
            'public'        => true,
            'menu_position' => 5,
            'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'has_archive'   => true
        );
        register_post_type('jq_goods', $goods_args);
    }
}
