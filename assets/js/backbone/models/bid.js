
Rfpez.Backbone.Bid = Backbone.Model.extend({
  validate: function(attrs) {
    var errors;
    errors = [];
    if (errors.length > 0) {
      return errors;
    }
  },
  clear: function() {
    return this.destroy();
  }
});
