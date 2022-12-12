<script>
	/*
	 * 追格积分商城小程序 v1.2.0
	 * Author: 追格
	 * Help document: https://www.zhuige.com
	 * Copyright © 2022 www.zhuige.com All rights reserved.
	 */

	import Vue from 'vue'

	export default {
		globalData: {
			appName: '积分商城',
			appDesc: '',
		},

		onLaunch: function() {
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
