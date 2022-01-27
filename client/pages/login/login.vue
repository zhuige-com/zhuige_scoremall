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

				<!-- #ifdef MP-WEIXIN -->
				<view v-if="code" class="jiangqie-button" @click="clickLogin()">授权登录</view>
				<!-- #endif -->

				<!-- #ifdef MP-QQ || MP-BAIDU -->
				<button v-if="code" open-type="getUserInfo" class="jiangqie-button"
					@getuserinfo="getuserinfo">授权登录</button>
				<!-- #endif -->

				<view class="jiangqie-button" @click="clickWalk()">随便逛逛</view>

				<view class="jiangqie-login-tip">授权登录即同意
					<text v-if="yhxy" @click="clickYhxy()">《用户协议》</text><template v-else>用户协议</template>
					及<text v-if="yszc" @click="clickYszc()">《隐私条款》</text><template v-else>隐私条款</template>
				</view>
			</view>

		</view>
	</view>
</template>

<script>
	import Constant from '@/utils/constants';
	import Auth from '@/utils/auth';
	import Util from '@/utils/util';
	import Api from '@/utils/api';
	import Rest from '@/utils/rest';

	export default {
		data() {
			return {
				background: '',
				logo: '',
				title: '',

				code: undefined,

				yhxy: '',
				yszc: '',
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
			clickLogin(e) {
				wx.getUserProfile({
					desc: '用于完善会员资料',
					success: res => {
						let userInfo = res.userInfo;
						this.login(userInfo.nickName, userInfo.avatarUrl);
					},
					fail: (err) => {
						console.log(err);
					}
				})
			},

			getuserinfo(res) {
				let userInfo = res.detail.userInfo;
				this.login(userInfo.nickName, userInfo.avatarUrl);
			},

			clickWalk() {
				Util.navigateBack();
			},

			clickYhxy() {
				Util.openLink(this.yhxy);
			},

			clickYszc() {
				Util.openLink(this.yszc);
			},

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
					Auth.setUser(res.data);
					Util.navigateBack();
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
