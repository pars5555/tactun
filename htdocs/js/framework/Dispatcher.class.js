/**
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2013-2014
 * @version 6.0
 */
NGS.Dispatcher = {

  baseUrl : "",
  loadsObject : {},

  initialize : function(baseUrl) {
    this.baseUrl = baseUrl;
    if (NGS.getInitialLoad()) {
      NGS.nestLoad(NGS.getInitialLoad().load, NGS.getInitialLoad().params);
    }
    if (NGS.getConfig().ajaxLoader) {
      document.getElementById(NGS.getConfig().ajaxLoader).style.display = "none";
    }
  },

  load : function(loadObject, params) {
    var _url = "";
    if (loadObject.getUrl() != "") {
      _url = this.computeUrl(loadObject.getUrl());
    } else {
      _url = this.computeUrl(loadObject.getPackage(), loadObject.getName());
    }

    var options = {
      method : loadObject.getMethod(),
      params : params,
      onComplete : function(responseText) {
        var res = JSON.parse(responseText);
        if ( typeof (res) == "object" && typeof (res.nl)) {
          for (var p in res.nl) {
            var nestedLoad = res.nl[p];
            for (var i = 0; i < nestedLoad.length; i++) {
              NGS.setNestedLoad(p, nestedLoad[i].load, nestedLoad[i].params);
            }
          }
        }
        loadObject.setArgs(res.params);
        loadObject.setPermalink(res.pl);
        loadObject._updateContent(res.html, res.params);
      }.bind(this),
      onError : function(responseText) {
        var res = JSON.parse(responseText);
        loadObject.onError(res);
      }.bind(this)
    };
    this.ajaxRequest(_url, options);
  },

  action : function(actionObject, params) {
    var _url = this.computeUrl(actionObject.getPackage(), "do_" + actionObject.getName());
    var options = {
      method : actionObject.getMethod(),
      params : params,
      onComplete : function(responseText) {
        try {
          var res = JSON.parse(responseText);
          actionObject.setArgs(res.params);
          actionObject.afterAction(res.params);
        } catch(e) {
          throw e;
        }
      }.bind(this),
      onError : function(responseText) {
        try {
          var res = JSON.parse(responseText);
          actionObject.onError(res);
        } catch(e) {
          throw e;
        }
      }.bind(this)
    };
    this.ajaxRequest(_url, options);
  },

  /**
   * Method for computing request URLs depending on the current security level, baseUrl, package and command, mainly used internaly by the framework,
   *
   * @param  command  htto name of the load or action: SomeLoad: some, SomeAction: do_some
   * @return computedUrl computed URL of the request
   * @see
   */
  computeUrl : function() {
    var _package = arguments[0].replace(".", "/");
    var command = "";
    switch(arguments.length) {
      case 2:
        command = arguments[1];
        break;
    }
    var dynContainer = "";
    if (NGS.getConfig().dynContainer != "") {
      dynContainer = "/" + NGS.getConfig().dynContainer + "/";
    }
    return this.baseUrl + dynContainer + _package + "/" + command;
  },

  /**
   * Method for ajax request handler
   *
   * @param  _url:String
   * @param  options:Object
   *
   */

  ajaxRequest : function(_url, options) {
    var defaultOptions = {
      method : "POST",
      async : true,
      params : {},
      onCreate : NGS.emptyFunction,
      onComplete : NGS.emptyFunction,
      on403 : NGS.emptyFunction
    };
    options = NGS.extend(defaultOptions, options);
    if (window.XMLHttpRequest) {
      var xmlhttp = new XMLHttpRequest();
    } else {
      var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 1) {
        if (NGS.getConfig().ajaxLoader) {
          document.getElementById(NGS.getConfig().ajaxLoader).style.display = "block";
        }
        options.onCreate();
      }
      if (xmlhttp.readyState == 4) {
        if (xmlhttp.status == 200) {
          options.onComplete(xmlhttp.responseText);
        } else if (xmlhttp.status == 403) {
          options.onError(xmlhttp.responseText);
        }
        if (NGS.getConfig().ajaxLoader) {
          document.getElementById(NGS.getConfig().ajaxLoader).style.display = "none";
        }
      }
    }.bind(this);

    var urlParams = this.serializeUrl(options.params);
    var sendingData = null;
    options.method = 'POST';
    if (options.method == 'POST') {
      sendingData = urlParams;
    } else {
      _url = _url + "?" + urlParams;
    }
    xmlhttp.open(options.method, _url, options.async);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(sendingData);
  },

  serializeUrl : function(obj, prefix) {
    var str = [];
    for (var p in obj) {
      if (obj.hasOwnProperty(p)) {
        if (obj[p] instanceof Array) {
          str.push(this.serializeArrayToUrl(obj[p], p));
          continue;
        }
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
      }
    }
    return str.join("&");
  },

  serializeArrayToUrl : function(urlArrayParams, prefix) {
    var str = [];
    for (var i = 0; i < urlArrayParams.length; i++) {
      if (urlArrayParams[i] instanceof Array) {
        str.push(this.serializeArrayToUrl(urlArrayParams[i], i));
        continue;
      }
      str.push(encodeURIComponent(prefix) + "[]=" + encodeURIComponent(urlArrayParams[i]));
    }
    return str.join("&");
  }
};

