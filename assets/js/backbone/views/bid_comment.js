
Rfpez.Backbone.BidCommentView = Backbone.View.extend({
  tagName: "div",
  className: "comment",
  template: _.template("<div class=\"body\">\n  <span class=\"author\">\n    <%- officer.name %>\n  </span>\n  <%- body %>\n</div>\n<span class=\"timestamp\">\n  <span class=\"posted-at\">Posted <span class=\"timeago\" title=\"<%- formatted_created_at %>\"></span></span>\n</span>\n<a class=\"delete-comment only-user only-user-<%- officer.user_id %>\">Delete</a>"),
  events: {
    "click a.delete-comment": "clear"
  },
  initialize: function() {
    this.model.bind("change", this.render, this);
    this.model.bind("destroy", this.remove, this);
    return this.parent_view = this.options.parent_view;
  },
  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
    this.$el.find(".timeago").timeago();
    return this;
  },
  clear: function() {
    if (this.parent_view) {
      this.parent_view.parent_view.decrementCommentCount();
    }
    return this.model.clear();
  }
});
