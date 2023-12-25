import Alert from '@/utils/alert';
import Auth from '@/utils/auth';

function navigateBack() {
	uni.navigateBack({
		delta: 1,
		fail: function(res) {
			uni.redirectTo({
				url: '/pages/index/index'
			});
		}
	});
}

/**
 *  把html转义成HTML实体字符
 * @param str
 * @returns {string}
 * @constructor
 */
function htmlEncode(str) {
	var s = "";
	if (str.length === 0) {
		return "";
	}
	s = str.replace(/&/g, "&amp;");
	s = s.replace(/</g, "&lt;");
	s = s.replace(/>/g, "&gt;");
	s = s.replace(/ /g, "&nbsp;");
	s = s.replace(/\'/g, "&#39;"); //IE下不支持实体名称
	s = s.replace(/\"/g, "&quot;");
	return s;
}

/**
 *  转义字符还原成html字符
 * @param str
 * @returns {string}
 * @constructor
 */
function htmlRestore(str) {
	var s = "";
	if (str.length === 0) {
		return "";
	}
	s = str.replace(/&amp;/g, "&");
	s = s.replace(/&lt;/g, "<");
	s = s.replace(/&gt;/g, ">");
	s = s.replace(/&nbsp;/g, " ");
	s = s.replace(/&#39;/g, "\'");
	s = s.replace(/&quot;/g, "\"");
	return s;
}

function openLink(link) {
	if (!link) {
		return;
	}

	link = htmlRestore(link);

	if (link.startsWith('/pages/')) {
		if (!Auth.getUser()) {
			let links = [
				'/pages/exchange/exchange',
				'/pages/record/record',
			];
			for (let i = 0; i < links.length; i++) {
				if (link.indexOf(links[i]) > -1) {
					uni.navigateTo({
						url: '/pages/login/login',
						fail(res) {
							uni.redirectTo({
								url: '/pages/login/login'
							});
						}
					});
					return;
				}
			}
		}

		uni.navigateTo({
			url: link,
			fail: () => {
				uni.redirectTo({
					url: link
				})
			}
		});
	} else if (link.startsWith('https://') || link.startsWith('http://')) {
		uni.navigateTo({
			url: '/pages/webview/webview?src=' + encodeURIComponent(link),
			fail: () => {
				uni.redirectTo({
					url: '/pages/webview/webview?src=' + encodeURIComponent(link)
				})
			}
		});
	} else {
		// #ifdef MP-WEIXIN
		if (link.startsWith('appid:')) {
			let appid = '';
			let page = '';
			let index = link.indexOf(';page:');
			if (index < 0) {
				appid = link.substring('appid:'.length);
			} else {
				appid = link.substring('appid:'.length, index);
				page = link.substring(index + ';page:'.length);
			}
			let params = {
				appId: appid,
				fail: res => {
					if (res.errMsg && res.errMsg.indexOf('cancel') < 0) {
						Alert.toast(res.errMsg)
					}
				}
			};
			if (page != '') {
				params.path = page;
			}

			uni.navigateToMiniProgram(params);
			return;
		} else if (link.startsWith('finder:')) {
			let finder = '';
			let feedId = '';
			let index = link.indexOf(';feedId:');
			if (index < 0) {
				finder = link.substring('finder:'.length);
			} else {
				finder = link.substring('finder:'.length, index);
				feedId = link.substring(index + ';feedId:'.length);
			}
			let params = {
				finderUserName: finder,
				fail: res => {
					if (res.errMsg && res.errMsg.indexOf('cancel') < 0) {
						Alert.toast(res.errMsg)
					}
				}
			};

			if (feedId != '') {
				params.feedId = feedId;
				wx.openChannelsActivity(params);
			} else {
				wx.openChannelsUserProfile(params);
			}

			return;
		}
		// #endif

		// #ifdef H5
		Alert.toast(link);
		// #endif

		// #ifndef H5
		uni.setClipboardData({
			data: link,
			fail: (res) => {
				if (res.errMsg && res.errMsg.indexOf('cancel') < 0) {
					Alert.error(res.errMsg);
				}
			}
		});
		// #endif
	}
}

/**
 * 是否弹窗
 */
function getPopAd(pop_ad, key) {
	if (!pop_ad) {
		return false;
	}

	let lastTime = wx.getStorageSync(key);
	if (!lastTime) {
		lastTime = 0;
	}

	let now = new Date().getTime();
	if ((now - lastTime) > pop_ad.interval * 3600000) {
		return pop_ad;
	}

	return false;
}

module.exports = {
	navigateBack,
	openLink,
	getPopAd
};