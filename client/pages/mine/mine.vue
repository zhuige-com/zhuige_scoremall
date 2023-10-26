<template>
	<view class="content" :style="background?'background: url(' + background + ') no-repeat;':''">
		<!-- 用户头像及信息 -->
		<view v-if="user" class="zhuige-user-info" @click="clickVerify">
			<image :src="user.avatar" mode="aspectFill"></image>
			<view>{{user.nickname}}</view>
		</view>
		<view v-else class="zhuige-user-info" @click="clickLogin">
			<image src="/static/images/default/avatar.jpg" mode="aspectFill"></image>
			<view>注册/登录</view>
		</view>

		<!-- 用户菜单 -->
		<view class="zhuige-user-menu">

			<view v-if="user" class="zhuige-list-block" @click="clickMyOrder">
				<view class="zhuige-base-list">
					<view class="zhuige-list-img">
						<image src="/static/images/icon_mall.png" mode="aspectFill"></image>
					</view>
					<view class="zhuige-list-info">
						<view class="zhuige-list-title">我的订单</view>
					</view>
				</view>
				<view class="zhugie-list-link">
					<uni-icons type="arrowright" size="14"></uni-icons>
				</view>
			</view>

			<!-- 列表块 -->
			<button open-type="contact" class="zhuige-list-block">
				<view class="zhuige-base-list">
					<view class="zhuige-list-img">
						<image src="/static/images/icon_contact.png" mode="aspectFill"></image>
					</view>
					<view class="zhuige-list-info">
						<view class="zhuige-list-title">在线客服</view>
					</view>
				</view>
				<view class="zhugie-list-link">
					<uni-icons type="arrowright" size="14"></uni-icons>
				</view>
			</button>

			<button open-type="feedback" class="zhuige-list-block">
				<view class="zhuige-base-list">
					<view class="zhuige-list-img">
						<image src="/static/images/icon_feedback.png" mode="aspectFill"></image>
					</view>
					<view class="zhuige-list-info">
						<view class="zhuige-list-title">反馈和建议</view>
					</view>
				</view>
				<view class="zhugie-list-link">
					<uni-icons type="arrowright" size="14"></uni-icons>
				</view>
			</button>

			<view v-if="page_about" class="zhuige-list-block" @click="clickAbout">
				<view class="zhuige-base-list">
					<view class="zhuige-list-img">
						<image src="/static/images/icon_about.png" mode="aspectFill"></image>
					</view>
					<view class="zhuige-list-info">
						<view class="zhuige-list-title">关于我们</view>
					</view>
				</view>
				<view class="zhugie-list-link">
					<uni-icons type="arrowright" size="14"></uni-icons>
				</view>
			</view>

			<view class="zhuige-list-block" @click="clickClear">
				<view class="zhuige-base-list">
					<view class="zhuige-list-img">
						<image src="/static/images/icon_clear.png" mode="aspectFill"></image>
					</view>
					<view class="zhuige-list-info">
						<view class="zhuige-list-title">清理缓存</view>
					</view>
				</view>
				<view class="zhugie-list-link">
					<uni-icons type="arrowright" size="14"></uni-icons>
				</view>
			</view>
		</view>

		<!-- 追格copyright -->
		<view v-if="copyright" class="zhuige-copyright">
			<image :src="copyright.logo" mode="aspectFill"></image>
			<view>{{copyright.text}}</view>
		</view>
		
		<!-- 备案信息 -->
		<view class="zhuige-recordinfo" v-if="beian_icp" @click="clickLink(beian_icp.link)">
			<text>
				{{beian_icp.sn}}
			</text>
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

	import Auth from '@/utils/auth';
	import Util from '@/utils/util';
	import Alert from '@/utils/alert';
	import Api from '@/utils/api';
	import Rest from '@/utils/rest';

	export default {
		components: {
			
		},
		
		data() {
			return {
				user: undefined,

				background: undefined,

				page_about: undefined,

				copyright: undefined,
				
				beian_icp: undefined,
			};
		},

		onLoad() {
			Rest.post(Api.ZG_SCOREMALL_SETTING_MINE).then(res => {
				this.background = res.data.background;
				this.page_about = res.data.page_about;
				this.copyright = res.data.copyright;
				
				if (res.data.beian_icp) {
					this.beian_icp = res.data.beian_icp;
				}
			}, err => {
				console.log(err);
			});
		},

		onShow() {
			this.user = Auth.getUser();
		},

		methods: {
			/**
			 * 点击打开链接
			 */
			clickLink(link) {
				Util.openLink(link);
			},
			
			/**
			 * 点击 打开完善用户信息页
			 */
			clickVerify() {
				Util.openLink('/pages/verify/verify');
			},

			/**
			 * 点击 打开登录页
			 */
			clickLogin() {
				if (Auth.getUser()) {
					return;
				}

				Util.openLink('/pages/login/login');
			},

			/**
			 * 点击 打开订单页
			 */
			clickMyOrder() {
				Util.openLink('/pages/record/record');
			},

			/**
			 * 点击 打开关于页面
			 */
			clickAbout() {
				Util.openLink(this.page_about);
			},

			/**
			 * 点击 清理缓存
			 */
			clickClear() {
				uni.showModal({
					title: '提示',
					content: '清除缓存 需要重新登录',
					success(res) {
						if (res.confirm) {
							if (Auth.getUser()) {
								Rest.post(Api.ZG_USER_LOGOUT).then(res => {
									console.log(res);
								}, err => {
									console.log(err);
								});
							}

							uni.clearStorageSync();
							uni.showToast({
								title: '清除完毕'
							});

							uni.reLaunch({
								url: '/pages/index/index'
							});
						}
					}
				});
			}
		}
	}
</script>

<style lang="scss">
	@import "@/style/style.scss";

	body,
	uni-page-body {
		height: 100%;
	}

	.zhuige-list-block {
		padding: 30rpx 0;
		border-bottom: 1px solid #EEEEEE;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.zhuige-list-block:last-of-type {
		border: none;
	}

	.zhuige-base-list {
		display: flex;
		align-items: center;
	}

	.zhuige-list-info {}

	.zhuige-list-title {
		font-size: 34rpx;
		font-weight: 600;
	}

	.zhuige-list-text {
		font-size: 24rpx;
		font-weight: 200;
	}

	.zhugie-list-link * {
		color: #999999;
	}

	.zhuige-user-info {
		text-align: center;
		padding: 100rpx;
	}

	.zhuige-user-info image {
		height: 160rpx;
		width: 160rpx;
		border-radius: 50%;
		margin-bottom: 20rpx;
	}

	.zhuige-user-info view {
		font-size: 36rpx;
		color: #FFFFFF;
	}

	.zhuige-copyright {
		text-align: center;
		padding: 40rpx;
	}

	.zhuige-user-menu {
		width: 100%;
		background: #FFFFFF;
	}

	.zhuige-user-menu .zhuige-list-block {
		padding: 0 30rpx;
		height: 4rem;
		line-height: 4rem;
	}

	.zhuige-list-block:last-of-type {
		border-bottom: 1px solid #EEEEEE;
	}

	.zhuige-user-menu .zhuige-list-img image {
		height: 40rpx;
		width: 40rpx;
		vertical-align: text-bottom;
		margin-right: 20rpx;
	}

	.zhuige-user-menu .zhuige-list-title {
		font-weight: 400;
		font-size: 28rpx;
	}

	.zhuige-user-menu button {
		border: none;
		background: #FFFFFF;
		border-bottom: 1px solid #EEEEEE;
		height: 4rem;
		line-height: 4rem;
	}

	.zhuige-user-menu button:after {
		border: none;
	}

	.zhuige-copyright image {
		height: 120rpx;
		width: 120rpx;
		border-radius: 50%;
		margin-bottom: 20rpx;
	}

	.zhuige-copyright view {
		font-size: 24rpx;
		color: #999999;
		font-weight: 200;
	}

	.zhuige-user-menu button {
		height: 4rem;
		line-height: 4rem;
	}
	
	/* 备案信息 */
	.zhuige-recordinfo {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 32rpx;
		// margin-bottom: 60rpx;
		line-height: 32rpx;
		font-size: 24rpx;
		font-weight: 400;
		color: #999999;
		
		text {
			padding-bottom: 20rpx;
		}
	}
</style>