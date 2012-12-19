
Rfpez.Backbone.BidComment = Backbone.Model.extend({
  validate: function(attrs) {
    if (!attrs.body) {
      return true;
    }
  },
  defaults: function() {
    return {
      owner: false,
      commentable_type: "vendor"
    };
  },
  clear: function() {
    return this.destroy();
  }
});
