NGS._UrlObserverByHistory = {
  _event : null,
  init : function() {
    var Backlen = history.length;
    if (Backlen > 0)
      history.go(-Backlen);
    if (window.history.forward(1) != null)
      window.history.forward(1);
    console.log(window.history.length);
    this._event = new CustomEvent("ngsUrlBind");
    document.addEventListener("ngsUrlChange", function(e) {
      this.setUrl(e.detail);
    }.bind(this));
    window.onpopstate = this.onpopstate.bind(this);
  },

  setUrl : function(urlObj) {
    history.pushState(urlObj.itemText, urlObj.item, urlObj.item);
  },
  onpopstate : function(e) {
    this._event.data = e.state;
    document.dispatchEvent(this._event);
  }
}; 