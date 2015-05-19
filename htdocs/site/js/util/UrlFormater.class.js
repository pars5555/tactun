/**
 * listener : function(location, oldLocation)
 */

NGS.UrlFormater = {
	staticUrls: {"artist": 1, "album": 1,"playlist":1},
	toObject : function(str) {
		var obj = [];
		if (str == "") {
			return obj;
		}
		var strArr = str.split("/");
		strArr.shift();

		obj[0] = {
			s : strArr[0]
		};
		var p = {};
		if(this.staticUrls[strArr[0]]){
			p["itemId"] = strArr[1];
			if(strArr[2]){
				p["trackId"] = strArr[2];
			}
			obj[0].p = p;
			return obj;
		}
		if (strArr.length == 1) {
			return obj;
		}
		
		var attCount = 0;
		for (var i = 1; i < strArr.length; i++) {
			if (strArr[i] == "") {
				break;
			}
			p[strArr[i]] = strArr[++i]; ++attCount;
		}
		if (attCount == 0) {
			return obj;
		}
		obj[0].p = p;
		
		return obj;
	},

	toString : function(obj) {
		var load = obj[0].load;
		var loadShourtcut = load.getShortCut();
		var _package = load.getPackage();
		if(loadShourtcut.indexOf("admin") === 0){
			loadShourtcut = _package.replace("_", "/")+loadShourtcut.replace("admin_", "/");
		}
		var str = loadShourtcut + "/";
		var params = load.getParams();
		if (load.getUrlParams()) {
			return str+load.getUrlParams();
		}
		for (key in params) {
			str += key + "/";
			str += params[key] + "/";
		}
		return str;
	}
}; 