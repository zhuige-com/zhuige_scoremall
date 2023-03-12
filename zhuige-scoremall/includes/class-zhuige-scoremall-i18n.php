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

class ZhuiGe_ScoreMall_i18n
{
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'zhuige-scoremall',
			false,
			dirname(ZHUIGE_SCOREMALL_BASE_NAME) . '/languages/'
		);
	}
}
