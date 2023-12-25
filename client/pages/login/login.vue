<template>
	<view class="content">
		<view class="jiangqie-login" :style="background?'background-image: url(' + background + ');':''">
			<view class="jiangqie-logo">
				<image mode="aspectFill" :src="logo"></image>
				<view v-if="title">{{title}}</view>
			</view>

			<view class="jiangqie-login-btn">
				<!-- #ifdef H5 -->
				<view class="jiangqie-login-tip">H5平台尚未适配</view>
				<!-- #endif -->

				<!-- #ifdef MP-WEIXIN || MP-QQ || MP-BAIDU -->
				<view v-if="code" class="jiangqie-button" @click="clickLogin()">授权登录</view>
				<!-- #endif -->

				<view class="jiangqie-button" @click="clickWalk()">随便逛逛</view>

				<view class="jiangqie-login-tip">
					<label @click="clickAgreeLicense">
						<radio :checked="argeeLicense" color="#ff4400" style="transform:scale(0.7)" />
						我已阅读并同意
					</label>
					<text v-if="yhxy" @click="clickYhxy()">《用户协议》</text><template v-else>用户协议</template>
					及<text v-if="yszc" @click="clickYszc()">《隐私条款》</text><template v-else>隐私条款</template>
				</view>
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

	import Constant from '@/utils/constants';
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
				background: '',
				logo: '',
				title: '',

				code: undefined,

				yhxy: '',
				yszc: '',
				argeeLicense: false,
			};
		},

		onLoad(options) {
			// #ifdef MP-WEIXIN || MP-QQ || MP-BAIDU
			uni.login({
				success: (res) => {
					this.code = res.code;
				}
			});
			// #endif

			Rest.post(Api.ZG_SCOREMALL_SETTING_LOGIN, {}).then(res => {
				this.background = res.data.background;
				this.logo = res.data.logo;
				this.title = res.data.title;
				this.yhxy = res.data.yhxy;
				this.yszc = res.data.yszc;
			}, err => {
				console.log(err)
			});
		},

		methods: {
			/**
			 * 点击 同意协议
			 */
			clickAgreeLicense() {
				this.argeeLicense = !this.argeeLicense;
			},

			/**
			 * 点击 登录
			 */
			clickLogin(e) {
				if (!this.argeeLicense) {
					Alert.toast('请阅读并同意《用户协议》及《隐私条款》');
					return;
				}

				// #ifdef MP-WEIXIN
				this.login('微信用户', '');
				// #endif

				// #ifdef MP-QQ
				this.login('QQ用户', '');
				// #endif

				// #ifdef MP-BAIDU
				this.login('百度用户', '');
				// #endif
			},

			/**
			 * 点击 返回
			 */
			clickWalk() {
				Util.navigateBack();
			},

			/**
			 * 点击 用户协议
			 */
			clickYhxy() {
				Util.openLink(this.yhxy);
			},

			/**
			 * 点击 隐私政策
			 */
			clickYszc() {
				Util.openLink(this.yszc);
			},

			/**
			 * 登录
			 */
			login(nickname, avatar) {
				let params = {
					code: this.code,
					nickname: nickname,
					avatar: avatar
				};

				// #ifdef MP-WEIXIN
				params.channel = 'weixin';
				// #endif

				// #ifdef MP-QQ
				params.channel = 'qq';
				// #endif

				// #ifdef MP-BAIDU
				params.channel = 'baidu';
				// #endif

				Rest.post(Api.ZG_SCOREMALL_USER_LOGIN, params).then(res => {
					if (res.code != 0) {
						uni.showModal({
							content: res.msg
						})
						return;
					}
					
					Auth.setUser(res.data);
					if (res.data.first && res.data.first == 1) {
						uni.redirectTo({
							url: '/pages/verify/verify'
						})
					} else {
						Util.navigateBack();
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

	.jiangqie-login {
		position: fixed;
		height: 100%;
		width: 100%;
		text-align: center;
		background-size: cover;
		background-position: center;
	}

	.jiangqie-logo {
		padding-top: 360rpx;
	}

	.jiangqie-logo image {
		height: 160rpx;
		width: 160rpx;
		border-radius: 50%;
	}

	.jiangqie-logo view {
		line-height: 4rem;
		color: $Q-login-color;
	}

	.jiangqie-login-btn {
		position: absolute;
		width: 100%;
		bottom: 140rpx;
	}

	.jiangqie-button {
		margin: 0 100rpx;
		line-height: 2.6rem;
		border-radius: 3rem;
		font-size: $Q-sub-info-size;
		font-weight: $Q-strong-btn-weight;
		margin-bottom: 1.2rem;
		border-color: $Q-login-border-color;
		background: none;
	}

	.jiangqie-login-tip {
		font-size: 22rpx;
		color: $Q-login-color;

		text {
			color: $Q-login-color;
			text-decoration: underline;
		}
	}
</style>
