const Config = require("@/utils/config");

function makeURL(module, action) {
	return `https://${Config.JQ_DOMAIN}/wp-json/zhuige-scoremall/${module}/${action}`;
}

module.exports = {
	
	// ---------- 文章 ----------
	
	/**
	 * 获取服务详情
	 */
	ZG_SCOREMALL_POST_PAGE: makeURL('post', 'page'),
	
	
	// ---------- 商品 ----------
	
	/**
	 * 获取最新商品列表
	 */
	ZG_SCOREMALL_GOODS_LAST: makeURL('goods', 'last'),
	
	/**
	 * 获取商品详情
	 */
	ZG_SCOREMALL_GOODS_DETAIL: makeURL('goods', 'detail'),
	
	/**
	 * 积分兑换准备
	 */
	ZG_SCOREMALL_GOODS_PRE_EXCHANGE: makeURL('goods', 'pre_exchange'),
	
	/**
	 * 积分兑换
	 */
	ZG_SCOREMALL_GOODS_EXCHANGE: makeURL('goods', 'exchange'),
	
	/**
	 * 积分兑换记录
	 */
	ZG_SCOREMALL_GOODS_RECORD: makeURL('goods', 'record'),
	
	// ---------- 配置 ----------
	
	/**
	 * 获取首页配置
	 */
	ZG_SCOREMALL_SETTING_HOME: makeURL('setting', 'home'),
	
	/**
	 * 获取我的配置
	 */
	ZG_SCOREMALL_SETTING_MINE: makeURL('setting', 'mine'),
	
	/**
	 * 获取登录配置
	 */
	ZG_SCOREMALL_SETTING_LOGIN: makeURL('setting', 'login'),
	
	// ---------- 用户 ----------
	
	/**
	 * 登录
	 */
	ZG_SCOREMALL_USER_LOGIN: makeURL('user', 'login'),
	
	/**
	 * 我的积分
	 */
	ZG_SCOREMALL_USER_SCORE: makeURL('user', 'score'),
};
