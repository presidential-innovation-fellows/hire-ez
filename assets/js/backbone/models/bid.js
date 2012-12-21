
Rfpez.Backbone.Bid = Backbone.Model.extend({
  validate: function(attrs) {
    var errors;
    errors = [];
    if (errors.length > 0) {
      return errors;
    }
  },
  defaults: function() {
    return {
      thumbs_downed: false,
      starred: false
    };
  },
  clear: function() {
    return this.destroy();
  },
  fetchDetails: function(cb) {
    var _this = this;
    return $.getJSON(this.url(), function(data) {
      _this.attributes.vendor = data.vendor;
      _this.change();
      return cb();
    });
  }
});
