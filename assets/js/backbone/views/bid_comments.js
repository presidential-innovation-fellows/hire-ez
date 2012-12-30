
Rfpez.Backbone.BidCommentsView = Backbone.View.extend({
  tagName: "div",
  className: "comments-list-wrapper",
  template: _.template("\n<div class=\"comments-list\"></div>\n<div class=\"comments-placeholder centered\"><img src=\"/img/spinner.gif\" /></div>\n\n<textarea></textarea>\n<button class=\"btn btn-primary btn-small\">Add Comment</button>"),
  initialize: function() {
    this.comments = new Rfpez.Backbone.BidCommentList();
    this.comments.bind('reset', this.reset, this);
    this.comments.bind('add', this.addOne, this);
    this.parent_view = this.options.parent_view;
    this.comments.url = "/vendors/" + this.options.vendor_id + "/comments";
    this.$el.html(this.template());
    return this.comments.fetch();
  },
  events: {
    "click button": "newComment"
  },
  reset: function() {
    this.$el.find(".comments-list").html('');
    return this.addAll();
  },
  addOne: function(comment) {
    var html, view;
    view = new Rfpez.Backbone.BidCommentView({
      model: comment,
      parent_view: this
    });
    html = view.render().el;
    return this.$el.find(".comments-list").append(html);
  },
  addAll: function() {
    this.$el.find(".comments-placeholder").hide();
    return this.comments.each(this.addOne, this);
  },
  newComment: function() {
    this.comments.create({
      officer: {
        name: $("body").data('officer-name'),
        user_id: $("body").data('officer-user-id')
      },
      body: this.$el.find("textarea").val(),
      formatted_created_at: new Date().toISOString()
    });
    this.$el.find("textarea").val('');
    if (this.parent_view) {
      return this.parent_view.incrementCommentCount();
    }
  }
});
