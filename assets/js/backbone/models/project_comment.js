
Rfpez.Backbone.ProjectComment = Backbone.Model.extend({
  validate: function(attrs) {
    if (!attrs.body) {
      return true;
    }
  },
  defaults: function() {
    return {
      owner: false,
      commentable_type: "project"
    };
  },
  clear: function() {
    return this.destroy();
  }
});
