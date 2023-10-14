<script>
	/*
	 * 追格积分商城小程序 v1.3.5
	 * 作者: 追格
	 * 文档: https://www.zhuige.com/docs/jf
	 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
	 * github: https://github.com/zhuige-com/zhuige_scoremall
	 * Copyright © 2022-2023 www.zhuige.com All rights reserved.
	 */

	import Vue from 'vue'
	
	export default {
		globalData: {
			appName: '积分商城',
			appDesc: '',
		},

		onLaunch() {
			uni.getSystemInfo({
				success: (e) => {
					Vue.prototype.$is_ios = (e.platform == 'ios');
				}
			})
		},

	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import "style/style.scss";
	@import "style/main.scss";
</style>