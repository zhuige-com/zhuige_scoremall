<template>
	<view class="content">
		<view v-if="goods && goods.slide && goods.slide.length>0" class="zhuige-points-goods-img">
			<swiper indicator-dots="true" autoplay="autoplay" circular="ture" indicator-color="rgba(255,255,255, 0.3)"
				indicator-active-color="rgba(255,255,255, 0.8)" interval="5000" duration="150" easing-function="linear">
				<swiper-item v-for="(item, index) in goods.slide" :key="index">
					<view>
						<image mode="aspectFill" :src="item.image.url"></image>
					</view>
				</swiper-item>
			</swiper>
		</view>
		<view v-if="goods" class="zhuige-point-goods">
			<view class="zhuige-point-goods-title">
				<view>
					<text>{{goods.title}}</text>
					<button open-type="share">
						<uni-icons type="redo" size="24"></uni-icons>
					</button>
				</view>
				<view class="zhuige-goods-subtitle">
					<text>{{goods.price}}积分</text>
					<text>已兑 {{goods.quantity}}</text>
				</view>
			</view>
			<view class="zhuige-point-goods-info">
				<view class="zhuige-point-goods-header">产品详情</view>
				<view class="zhuige-point-goods-cont">
					<mp-html :content="goods.content" />
				</view>
			</view>
			<view class="zhuige-point-goods-btn">
				<button open-type="contact"><image mode="aspectFill" src="/static/images/contact.png"></image></button>
				<view class="view" @click="clickLink('/pages/exchange/exchange?goods_id=' + goods_id)">立即兑换</view>
			</view>
		</view>
	</view>
</template>

<script>
	/*
	 * 追格积分商城小程序
	 * 作者: 追格
	 * 文档: https://www.zhuige.com/docs/jf
	 * gitee: https://gitee.com/zhuige_com/zhuige_scoremall
	 * github: https://github.com/zhuige-com/zhuige_scoremall
	 * Copyright © 2022-2023 www.zhuige.com All rights reserved.
	 */

	import Util from '@/utils/util';
	import Alert from '@/utils/alert';
	import Api from '@/utils/api';
	import Rest from '@/utils/rest';
	import mpHtml from '@/components/mp-html/mp-html'

	export default {
		data() {
			return {
				goods_id: 0,
				goods: undefined,
			};
		},

		components: {
			mpHtml
		},

		onLoad(options) {
			this.goods_id = options.goods_id;

			this.loadGoods();
		},

		onShareAppMessage() {
			return {
				title: this.goods.title,
				path: 'pages/goods/goods?goods_id=' + this.goods_id
			};
		},

		// #ifdef MP-WEIXIN
		onShareTimeline() {
			return {
				title: this.goods.title
			};
		},
		// #endif

		onPullDownRefresh() {
			this.loadGoods();
		},

		methods: {
			/**
			 * 点击 打开链接
			 */
			clickLink(link) {
				Util.openLink(link);
			},

			/**
			 * 加载 商品信息
			 */
			loadGoods() {
				Rest.post(Api.ZG_SCOREMALL_GOODS_DETAIL, {
					post_id: this.goods_id
				}).then(res => {
					if (res.code == 0) {
						this.goods = res.data;
					} else {
						Alert.toast(res.msg);
					}

					uni.stopPullDownRefresh();
				}, err => {
					console.log(err)
				});
			}
		}
	}
</script>

<style lang="scss">
	@import "@/style/style.scss";

	page,
	.content {
		background: #F5F5F5;
	}

	.zhuige-points-goods-img {
		position: relative;
	}

	.zhuige-points-goods-img,
	.zhuige-points-goods-img swiper,
	.zhuige-points-goods-img swiper-item,
	.zhuige-points-goods-img image {
		height: 600rpx;
	}

	.zhuige-point-goods {
		position: relative;
		margin-top: -50rpx;
		padding: 0 0 200rpx;
		z-index: 2;
	}

	.zhuige-point-goods-title {
		background: #FFFFFF;
		border-radius: 16rpx;
		margin-bottom: 30rpx;
	}

	.zhuige-point-goods-title view:nth-child(1) {
		display: flex;
		justify-content: space-between;
		padding: 50rpx 30rpx 30rpx;
		font-weight: 600;
		font-size: 34rpx;
		border-bottom: 1rpx solid #DDDDDD;
		
		button {
			display: flex;
			align-items: center;
			margin: 0;
			padding: 0;
			line-height: 34rpx;
			background-color: transparent;
		}
	}

	.zhuige-point-goods-title view:nth-child(2) text:nth-child(2) {
		font-size: 26rpx;
		font-weight: 200;
		color: #666666;
	}

	.zhuige-point-goods-title view:nth-child(2) {
		padding: 20rpx 30rpx 30rpx;
		display: flex;
		align-items: center;
	}

	.zhuige-point-goods-title view:nth-child(2) text:nth-child(1) {
		font-size: 40rpx;
		font-weight: 600;
		color: #dd524d;
		padding-right: 20rpx;
	}
	
	.zhuige-goods-subtitle {
		display: flex;
		justify-content: space-between;
	}

	.zhuige-point-goods-info {
		padding: 30rpx;
		margin-bottom: 30rpx;
		background: #FFFFFF;
		border-radius: 16rpx;
	}

	.zhuige-point-goods-header {
		font-size: 36rpx;
		font-weight: 600;
		padding-bottom: 30rpx;
	}

	.zhuige-point-goods-btn {
		display: flex;
		width: 70%;
		margin: 0 15%;
		position: fixed;
		bottom: 80rpx;
	}

	.zhuige-point-goods-btn .view {
		margin-left: 5rpx;
		height: 3rem;
		line-height: 3rem;
		text-align: center;
		border-radius: 12rpx;
		color: #FFFFFF;
		font-weight: 400;
	}

	.zhuige-point-goods-btn .view image {
		height: 60rpx;
		width: 60rpx;
		margin-top: 16rpx;
	}
	
	.zhuige-point-goods-btn>button {
		display: flex;
		align-items: center;
		border: 1px solid #dd524d;
	}
	
	.zhuige-point-goods-btn>button>image {
		height: 60rpx;
		width: 60rpx;
	}

	.zhuige-point-goods-btn .view {
		width: 100%;
		background: #dd524d;
	}

	/* --- 自定义轮播图指示点 --- */
	.zhuige-points-goods-img swiper .wx-swiper-dot,
	.zhuige-points-goods-img uni-swiper .uni-swiper-dot {
		margin-bottom: 50rpx;
	}
</style>
