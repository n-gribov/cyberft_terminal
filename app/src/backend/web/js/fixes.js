/**
 * Created by vk on 14.10.14.
 */
jQuery(document).ready(function () {
	yii.getQueryParams = function (url) {
		var pos = url.indexOf('?');
		if (pos < 0) {
			return {};
		}
		var qs = url.substring(pos + 1).split('&');
		var qn,
			prevqn,
			qncounter = 0;
		for (var i = 0, result = {}; i < qs.length; i++) {
			qs[i] = qs[i].split('=');
			qn = decodeURIComponent(qs[i][0]);
			if (prevqn === qn && '[]' === qn.substr(-2)) {
				qncounter++;
				result[qn.replace('[]', '[' + qncounter + ']')] = decodeURIComponent(qs[i][1]);
			} else {
				result[qn] = decodeURIComponent(qs[i][1]);
				qncounter = 0;
			}
			prevqn = qn;
		}
		return result;
	}
});