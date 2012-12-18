
Rfpez.Backbone.BidModalView = Backbone.View.extend({
  tagName: "div",
  className: "modal hide",
  template: _.template("<div class=\"modal-header\">\n  <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>\n  <h3>Modal header</h3>\n</div>\n<div class=\"modal-body\">\n  <p>One fine body…</p>\n</div>\n<div class=\"modal-footer\">\n</div>"),
  initialize: function() {
    this.render();
    return this;
  },
  render: function() {
    return this.$el.html(this.template(this.model.toJSON()));
  },
  clear: function() {
    return this.model.clear();
  }
});
