<?php

/*
 * 追格积分商城Free
 * Author: 追格
 * Help document: https://www.zhuige.com/owfree/7685.html
 * github: https://github.com/zhuige-com/zhuige_scoremall
 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
 * License：GPL-2.0
 * Copyright © 2022-2024 www.zhuige.com All rights reserved.
 */

if (!class_exists('ZhuiGe_ScoreMall_User_Property')) {
	class ZhuiGe_ScoreMall_User_Property
	{
		public function __construct()
		{
			add_action('show_user_profile', array($this, 'edit_user_profile'));
			add_action('edit_user_profile', array($this, 'edit_user_profile'));

			add_action('personal_options_update', array($this, 'edit_user_profile_update'));
			add_action('edit_user_profile_update', array($this, 'edit_user_profile_update'));
		}

		public function edit_user_profile($profileuser)
		{
?>
			<h3>追格属性</h3>
			<table class="form-table">
				<tr>
					<th><label for="zhuige_score">追格积分</label></th>
					<td colspan="2">
						<input type="text" name="zhuige_score" id="zhuige_score" value="<?php echo get_user_meta($profileuser->ID, 'zhuige_score', true); ?>" class="regular-text" /><br />
						<span class="description">请输入追格积分</span>
					</td>
				</tr>
			</table>
<?php
		}

		public function edit_user_profile_update($user_id)
		{
			//修改积分
			//记录积分账单
			$new_score = (int)($_POST['zhuige_score']);
			$old_score = (int)(get_user_meta($user_id, 'zhuige_score', true));
			global $wpdb;
			$wpdb->insert("{$wpdb->prefix}zhuige_scoremall_score_bills", [
				'user_id' => $user_id,
				'action' => 'admin',
				'extra' => '',
				'score' => $new_score - $old_score,
				'time' => time()
			]);
			update_user_meta($user_id, 'zhuige_score', $new_score);
		}

	}
}

if (!isset($zhuige_scoremall_user_property))
	$zhuige_scoremall_user_property = new ZhuiGe_ScoreMall_User_Property;