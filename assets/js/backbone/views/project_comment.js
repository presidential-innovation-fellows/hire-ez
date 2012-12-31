
Rfpez.Backbone.ProjectCommentView = Backbone.View.extend({
  tagName: "div",
  className: "well comment",
  template: _.template("<div class=\"body\">\n  <span class=\"author\">\n    <%- officer.name %>\n  </span>\n  <%- body %>\n</div>\n<span class=\"timestamp\">\n  <span class=\"posted-at\">Posted <span class=\"timeago\" title=\"<%- formatted_created_at %>\"></span></span>\n</span>\n<a class=\"delete-comment only-user only-user-<%- officer.user_id %>\">Delete</a>"),
  events: {
    "click a.delete-comment": "clear"
  },
  initialize: function() {
    this.model.bind("change", this.render, this);
    return this.model.bind("destroy", this.remove, this);
  },
  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
    this.$el.find(".timeago").timeago();
    return this;
  },
  clear: function() {
    return this.model.clear();
  }
});
