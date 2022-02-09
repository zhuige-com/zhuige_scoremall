<template>
	<view class="content">
		<template v-if="orders.length>0">
			<view v-for="(item,index) in orders" :key="index" class="zhuige-exchange">
				<view class="zhuige-exchange-goods">
					<image :src="item.goods_image" mode="aspectFill"></image>
					<view class="zhuige-exchange-info">
						<view>{{item.goods_name}}</view>
						<text>{{item.goods_price}}积分</text>
					</view>
				</view>
				<view class="zhuige-exchange-contact">
					<view>
						<uni-icons type="location-filled" size="20" color="#999999"></uni-icons>
					</view>
					<view class="zhuige-exchange-contact-info">
						<view>{{item.addressee}} - {{item.mobile}}</view>
						<view>地址：{{item.address}}</view>
						<view>备注：{{item.remark}}</view>
						<view v-if="item.express_type && item.express_no" class="zhuige-exchange-express_no">
							{{item.express_type}}：<text>{{item.express_no}}</text>
						</view>
					</view>
				</view>
				<view class="zhuige-exchange-footer">
					<view>订单号：{{item.trade_no}}</view>
					<view>{{item.createtime}}</view>
				</view>
			</view>
			
			<uni-load-more :status="loadMore"></uni-load-more>
		</template>
		<template v-else>
			<jiangqie-no-data v-if="loaded"></jiangqie-no-data>
		</template>
	</view>
</template>

<script>
	import Util from '@/utils/util';
	import Alert from '@/utils/alert';
	import Api from '@/utils/api';
	import Rest from '@/utils/rest';
	import JiangqieNoData from "@/components/nodata/nodata";

	export default {
		data() {
			return {
				orders: [],
				loadMore: 'more',
				loaded: false,
			};
		},

		components: {
			JiangqieNoData,
		},

		onLoad(options) {
			this.loadOrders();
		},

		onReachBottom() {
			if (this.loadMore == 'more') {
				this.loadOrders();
			}
		},

		onPullDownRefresh() {
			this.orders = [];
			this.loadOrders();
		},

		methods: {
			clickLink(link) {
				Util.openLink(link);
			},

			loadOrders: function() {
				if (this.loadMore == 'loading') {
					return;
				}
				this.loadMore = 'loading';

				Rest.post(Api.ZG_SCOREMALL_GOODS_RECORD, {
					offset: this.orders.length,
				}).then(res => {
					this.orders = this.orders.concat(res.data.orders);
					this.loadMore = res.data.more;
					this.loaded = true;

					uni.stopPullDownRefresh();
				});
			},

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
		-webkit-flex: 0 0 180rpx;
		-ms-flex: 0 0 180rpx;
		flex: 0 0 180rpx;
		height: 120rpx;
		width: 180rpx;
		border-radius: 12rpx;
	}

	.zhuige-exchange-info {
		margin-left: 20rpx;
	}

	.zhuige-exchange-contact {
		display: flex;
		align-items: center;
		border-bottom: 1rpx solid #DDDDDD;
	}

	.zhuige-exchange-contact-info {
		padding: 10rpx;
	}

	.zhuige-exchange-contact-info view {
		font-size: 26rpx;
		font-weight: 200;
	}

	.zhuige-exchange-contact-info view:nth-child(2),
	.zhuige-exchange-contact-info view:nth-child(3) {
		color: #999999;
	}
	
	.zhuige-exchange-footer {
		display: flex;
		justify-content: space-between;
		padding-top: 10rpx;
		color: #999999;
	}
</style>
