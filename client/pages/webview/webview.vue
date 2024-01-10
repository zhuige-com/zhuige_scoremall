<template>
	<view>
		<web-view v-if="src" :src="src"></web-view>
	</view>
</template>

<script>
	/*
	 * 追格积分商城小程序
	 * 作者: 追格
	 * 文档: https://www.zhuige.com/docs/jf
	 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
	 * github: https://github.com/zhuige-com/zhuige_scoremall
	 * Copyright © 2022-2024 www.zhuige.com All rights reserved.
	 */

	export default {
		data() {
			return {
				src: ''
			};
		},

		onLoad(options) {
			if (!options.src) {
				uni.reLaunch({
					url: '/pages/index/index'
				})
				return;
			}
			
			this.src = decodeURIComponent(options.src);
		},

		onShareAppMessage(options) {
			return {
				title: getApp().globalData.appName,
				path: 'pages/webview/webview?src=' + encodeURIComponent(options.webViewUrl)
			};
		}
	}
</script>

<style lang="scss">

</style>
