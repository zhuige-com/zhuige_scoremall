<script>
	/*
	 * 追格积分商城小程序 v1.3.13
	 * 作者: 追格
	 * 文档: https://www.zhuige.com/docs/jf
	 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
	 * github: https://github.com/zhuige-com/zhuige_scoremall
	 * Copyright © 2022-2024 www.zhuige.com All rights reserved.
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
			
			const updateManager = wx.getUpdateManager()
			updateManager.onCheckForUpdate(function(res) {
				// 请求完新版本信息的回调
				// console.log(res.hasUpdate)
			})
			updateManager.onUpdateReady(function() {
				wx.showModal({
					title: '更新提示',
					content: '新版本已经准备好，是否重启应用？',
					success: function(res) {
						if (res.confirm) {
							// 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
							updateManager.applyUpdate()
						}
					}
				})
			})
			updateManager.onUpdateFailed(function() {
				// 新版本下载失败
			})
		},

	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import "style/style.scss";
	@import "style/main.scss";
</style>