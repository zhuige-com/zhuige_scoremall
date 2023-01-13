<template>
	<view class="content">
		<view class="zhuige-exchange">
			<view v-if="goods" class="zhuige-exchange-goods">
				<image :src="goods.thumbnail" mode="aspectFill"></image>
				<view class="zhuige-exchange-info">
					<view>{{goods.title}}</view>
					<text>{{goods.price}}积分</text>
				</view>
			</view>
			<view class="zhuige-exchange-form">
				<view class="zhuige-exchange-title">联系信息：</view>
				<view class="zhuige-exchange-form-line">
					<view>姓名：</view>
					<input type="text" v-model="addressee" />
				</view>
				<view class="zhuige-exchange-form-line">
					<view>电话/微信/邮箱：</view>
					<input type="text" v-model="mobile" placeholder="如为实物请预留电话，以便发货哦" />
				</view>
				<view class="zhuige-exchange-form-line">
					<view>地址：</view>
					<input type="text" v-model="address" />
				</view>
				<view class="zhuige-exchange-form-line">
					<view>备注：</view>
					<textarea v-model="remark" placeholder="想说点什么吗？"></textarea>
				</view>
				<view @click="clickExchange()" class="zhuige-exchange-btn">支付{{goods.price}}积分，立即兑换</view>
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

	export default {
		data() {
			this.goods_id = 0;

			return {
				goods: undefined,

				addressee: '',
				mobile: '',
				address: '',
				remark: ''
			};
		},

		onLoad(options) {
			this.goods_id = options.goods_id;

			this.loadGoods();
		},

		onPullDownRefresh() {
			this.loadGoods();
		},

		methods: {
			/**
			 * 点击打开链接
			 */
			clickLink(link) {
				Util.openLink(link);
			},

			/**
			 * 加载商品信息
			 */
			loadGoods() {
				Rest.post(Api.ZG_SCOREMALL_GOODS_PRE_EXCHANGE, {
					post_id: this.goods_id
				}).then(res => {
					this.goods = res.data;

					uni.stopPullDownRefresh();
				}, err => {
					console.log(err)
				});
			},

			/**
			 * 点击交换商品
			 */
			clickExchange() {
				Rest.post(Api.ZG_SCOREMALL_GOODS_EXCHANGE, {
					post_id: this.goods_id,
					addressee: this.addressee,
					mobile: this.mobile,
					address: this.address,
					remark: this.remark
				}).then(res => {
					Alert.toast(res.msg);
					if (res.code == 0) {
						uni.redirectTo({
							url: '/pages/record/record'
						})
					}
				}, err => {
					console.log(err)
				});
			}
		}
	}
</script>

<style lang="scss">
	@import "@/style/style.scss";

	.zhuige-exchange {
		border-top: 24rpx solid #F3F3F3;
		padding: 30rpx;
	}

	.zhuige-exchange-goods {
		display: flex;
		align-items: center;
		position: relative;
		padding-bottom: 30rpx;
		border-bottom: 1rpx solid #DDDDDD;
	}

	.zhuige-exchange-goods image {
		-webkit-box-flex: 0;
		-webkit-flex: 0 0 240rpx;
		-ms-flex: 0 0 240rpx;
		flex: 0 0 240rpx;
		height: 180rpx;
		width: 240rpx;
		border-radius: 12rpx;
	}

	.zhuige-exchange-info {
		margin-left: 20rpx;
	}

	.zhuige-exchange-form {
		padding: 30rpx 0;
	}

	.zhuige-exchange-title {
		font-size: 36rpx;
		font-weight: 600;
		padding-bottom: 20rpx;
	}

	.zhuige-exchange-form-line {
		display: flex;
		flex-wrap: nowrap;
		padding: 20rpx;
		background: #F5F5F5;
		margin-bottom: 30rpx;
		border-radius: 12rpx;
	}

	.zhuige-exchange-form-line view {
		word-break: keep-all;
		font-size: 28rpx;
		font-weight: 300;
		padding-right: 20rpx;
	}

	.zhuige-exchange-form-line input {
		font-size: 28rpx;
		line-height: 1.8rem;
		height: 1.8rem;
		overflow: hidden;
		min-width: 62%;
		font-weight: 400;
	}

	.zhuige-exchange-form-line textarea {
		font-size: 28rpx;
		line-height: 1.8rem;
		height: 200rpx;
		overflow: hidden;
		padding-top: 8rpx;
		min-width: 62%;
	}

	.zhuige-exchange-btn {
		margin: 30rpx 100rpx;
		border-radius: 12rpx;
		line-height: 3rem;
		height: 3rem;
		text-align: center;
		background: #dd524d;
		color: #FFFFFF;
		font-size: 28rpx;
		font-weight: 200;
	}
</style>
