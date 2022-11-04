<template>
	<view>
		<web-view :src="src"></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				src: 'https://www.jiangqie.com/'
			};
		},

		onLoad(options) {
			if (options.src) {
				this.src = decodeURIComponent(options.src);
			}
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
