<template>
	<view class="content">
		<view v-if="slides && slides.length>0" class="zhuige-swiper">
			<swiper indicator-dots="true" autoplay="autoplay" circular="ture" indicator-color="rgba(255,255,255, 0.3)"
				indicator-active-color="rgba(255,255,255, 0.8)" interval="5000" duration="150" easing-function="linear">
				<swiper-item v-for="(slide, index) in slides" :key="index" @click="clickLink(slide.link)">
					<image :src="slide.image" mode="aspectFill"></image>
				</swiper-item>
			</swiper>
		</view>

		<view v-if="icon_navs.length>0" class="jiangqie-icon-block">
			<view v-for="(item,index) in icon_navs" :key="index" @click="clickLink(item.link)"
				class="jiangqie-custom-icon">
				<image mode="aspectFill" :src="item.image"></image>
				<text>{{item.title}}</text>
			</view>
		</view>

		<view v-if="events" class="zhuige-point-store-box">
			<view class="zhuige-point-store-title">
				<view>{{events.title}}</view>
				<view>{{events.sub_title}}</view>
			</view>
			<view class="zhuige-grid-ad">
				<view class="zhuige-grid-left" @click="clickLink(events.event.l_url)">
					<image :src="events.event.l_image" mode="aspectFill"></image>
					<text>{{events.event.l_title}}</text>
				</view>
				<view class="zhuige-grid-rihgt">
					<view class="zhuige-grid-rihgt-top" @click="clickLink(events.event.rt_url)">
						<image :src="events.event.rt_image" mode="aspectFill"></image>
						<text>{{events.event.rt_title}}</text>
					</view>
					<view class="zhuige-grid-rihgt-end" @click="clickLink(events.event.rd_url)">
						<image :src="events.event.rd_image" mode="aspectFill"></image>
						<text>{{events.event.rd_title}}</text>
					</view>
				</view>
			</view>
		</view>

		<template v-if="goods_list.length>0">
			<view class="zhuige-point-store-box">
				<view class="zhuige-point-store-title">
					<view>积分好货</view>
					<view v-if="is_login" @click="clickMyScore">
						我的积分
						<text>{{my_score}}</text>
					</view>
					<view v-else @click="clickLogin">
						登录查看积分
					</view>
				</view>
				<view class="zhuige-point-store-list">
					<view v-for="(item,index) in goods_list" :key="index" class="zhuige-point-store-list-block"
						@click="clickLink('/pages/goods/goods?goods_id=' + item.id)">
						<image :src="item.thumbnail" mode="aspectFill"></image>
						<text v-if="item.badge" class="badge">{{item.badge}}</text>
						<view class="zhuige-point-store-list-text">
							<view class="zhuige-point-store-list-title">{{item.title}}</view>
							<view class="zhuige-point-store-list-opt">{{item.price}}积分</view>
						</view>
					</view>
				</view>
			</view>
			<uni-load-more :status="loadMore"></uni-load-more>
		</template>
		<template v-else>
			<jiangqie-no-data v-if="loaded"></jiangqie-no-data>
		</template>
		
		<view v-if="pop_ad" class="zhugie-pop-cover">
			<view @click="clickPopAd" class="zhuige-pop-box">
				<image mode="aspectFit" :src="pop_ad.image"></image>
				<view>
					<uni-icons @click="clickPopAdClose" type="close" size="32" color="#FFFFFF"></uni-icons>
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
	 * Copyright © 2022-2024 www.zhuige.com All rights reserved.
	 */

	import Constants from '@/utils/constants';
	import Auth from '@/utils/auth';
	import Util from '@/utils/util';
	import Alert from '@/utils/alert';
	import Api from '@/utils/api';
	import Rest from '@/utils/rest';
	import JiangqieNoData from "@/components/nodata/nodata";

	export default {
		components: {
			JiangqieNoData
		},
		
		data() {
			this.share_title = undefined;
			this.share_thumb = undefined;

			return {
				slides: [],

				icon_navs: [],

				my_score: undefined,
				events: undefined,

				goods_list: [],
				loadMore: 'more',
				loaded: false,

				is_login: false,
				
				// 弹窗广告
				pop_ad: undefined,
			};
		},

		onLoad(options) {
			this.loadSetting();
			this.loadGoods();
		},

		onShow() {
			this.is_login = !!(Auth.getUser());

			if (this.is_login) {
				Rest.post(Api.ZG_SCOREMALL_USER_SCORE).then(res => {
					this.my_score = res.data.my_score;
				}, err => {
					console.log(err)
				});
			}
		},

		onShareAppMessage() {
			let params = {
				title: getApp().globalData.appDesc + '_' + getApp().globalData.appName,
				path: 'pages/index/index'
			};

			if (this.share_title && this.share_title.length > 0) {
				params.title = this.share_title;
			}

			if (this.share_thumb) {
				params.imageUrl = this.share_thumb;
			}

			return params;
		},

		// #ifdef MP-WEIXIN
		onShareTimeline() {
			return {
				title: getApp().globalData.appName
			};
		},
		// #endif

		onReachBottom() {
			if (this.loadMore == 'more') {
				this.loadGoods();
			}
		},

		onPullDownRefresh() {
			this.refresh();
		},

		methods: {
			/**
			 * 点击打开链接
			 */
			clickLink(link) {
				Util.openLink(link);
			},

			/**
			 * 点击 查看积分说明
			 */
			clickMyScore() {
				Util.openLink('/pages/page/page?page_id=2812');
			},

			/**
			 * 点击 打开登录页面
			 */
			clickLogin() {
				Util.openLink('/pages/login/login');
			},

			/**
			 * 刷新
			 */
			refresh() {
				this.loadSetting();

				this.goods_list = [];
				this.loadGoods();
			},

			/**
			 * 加载配置
			 */
			loadSetting() {
				Rest.post(Api.ZG_SCOREMALL_SETTING_HOME).then(res => {
					getApp().globalData.appName = res.data.title;
					getApp().globalData.appDesc = res.data.desc;

					this.slides = res.data.slides;
					this.icon_navs = res.data.icon_navs;

					this.my_score = res.data.my_score;
					this.events = res.data.events;

					this.share_title = res.data.home_title;
					this.share_thumb = res.data.thumb;
					
					// 弹框
					this.pop_ad = Util.getPopAd(res.data.pop_ad, Constants.ZHUIGE_INDEX_MAXAD_LAST_TIME);

					uni.stopPullDownRefresh();
				}, err => {
					console.log(err)
				});
			},

			/**
			 * 加载商品列表
			 */
			loadGoods() {
				if (this.loadMore == 'loading') {
					return;
				}
				this.loadMore = 'loading';

				Rest.post(Api.ZG_SCOREMALL_GOODS_LAST, {
					offset: this.goods_list.length
				}).then(res => {
					this.goods_list = this.goods_list.concat(res.data.list);
					this.loadMore = res.data.more;
					this.loaded = true;
				});
			},
			
			/**
			 * 点击弹出窗口
			 */
			clickPopAd() {
				wx.setStorageSync(Constants.ZHUIGE_INDEX_MAXAD_LAST_TIME, new Date().getTime())
				Util.openLink(this.pop_ad.link);
				this.pop_ad = false;
			},
			
			/**
			 * 关闭弹出窗口
			 */
			clickPopAdClose() {
				this.pop_ad = false;
				wx.setStorageSync(Constants.ZHUIGE_INDEX_MAXAD_LAST_TIME, new Date().getTime())
			},
		}
	}
</script>

<style lang="scss">
	@import "@/style/style.scss";

	page,
	.content {
		background: #F5F5F5;
	}

	.content {
		padding-bottom: 30rpx;
	}

	.zhuige-swiper {
		margin-bottom: 30rpx;
	}

	.zhuige-swiper swiper,
	.zhuige-swiper swiper-item,
	.zhuige-swiper image {
		height: 400rpx;
		width: 100%;
	}

	.jiangqie-icon-block {
		padding: 20rpx 30rpx 10rpx;
		background: #FFFFFF;
		border-radius: 12rpx;
		margin-bottom: 30rpx;
	}

	.jiangqie-icon-block .jiangqie-custom-icon {
		margin-bottom: 10rpx;
	}

	.zhuige-grid-ad {
		display: flex;
		padding-top: 20rpx;
	}

	.zhuige-grid-ad text {
		position: absolute;
		z-index: 3;
		background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.4));
		color: #FFFFFF;
		font-size: 24rpx;
		font-weight: 400;
		left: 0;
		bottom: 0;
		height: 2.4rem;
		line-height: 2.6rem;
		padding: 0 7%;
		width: 86%;
	}

	.zhuige-grid-left {
		position: relative;
		height: 360rpx;
		width: 49%;
		margin-right: 2%;
		border-radius: 12rpx 0 0 12rpx;
		overflow: hidden;
	}

	.zhuige-grid-left image {
		height: 100%;
		width: 100%;
	}

	.zhuige-grid-rihgt {
		height: 360rpx;
		width: 49%;
	}

	.zhuige-grid-rihgt-top {
		display: flex;
		height: 175rpx;
		width: 100%;
		margin-bottom: 10rpx;
		border-radius: 0 12rpx 0 0;
		overflow: hidden;
		position: relative;
	}

	.zhuige-grid-rihgt-top image {
		height: 100%;
		width: 100%;
		object-fit: cover;
	}

	.zhuige-grid-rihgt-end {
		position: relative;
		height: 175rpx;
		width: 100%;
		border-radius: 0 0 12rpx 0;
		overflow: hidden;
	}

	.zhuige-grid-rihgt-end image {
		height: 100%;
		width: 100%;
		object-fit: cover;
	}

	.zhuige-load-none {
		font-size: 26rpx;
		font-weight: 200;
		color: #666666;
		padding: 40rpx;
		text-align: center;
	}
	
	/**
	 * 弹窗 start
	 */
	.zhugie-pop-cover {
		position: fixed;
		height: 100%;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		background: rgba(0, 0, 0, .6);
		z-index: 998;
		top: 0;
		left: 0;
	}
	
	.zhuige-pop-box {
		width: 600rpx;
		height: 600rpx;
		position: relative;
		text-align: center;
	}
	
	.zhuige-pop-box image {
		height: 100%;
		width: 100%;
	}
	
	.zhuige-pop-box view {
		position: absolute;
		bottom: -48rpx;
		height: 48rpx;
		width: 48rpx;
		z-index: 999;
		left: 50%;
		margin-left: -24rpx;
	}
	/**
	 * 弹窗 end
	 */
</style>