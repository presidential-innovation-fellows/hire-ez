
Rfpez.Backbone.BidCommentsView = Backbone.View.extend({
  tagName: "div",
  className: "comments-list-wrapper",
  template: _.template(" <div class=\"comments-list\"></div>\n\n<textarea></textarea>\n<button class=\"btn btn-primary btn-small\">Add Comment</button>"),
  initialize: function() {
    this.comments = new Rfpez.Backbone.BidCommentList();
    this.comments.bind('reset', this.reset, this);
    this.comments.bind('add', this.addOne, this);
    this.parent_view = this.options.parent_view;
    this.comments.url = "/bids/" + this.options.bid_id + "/comments";
    this.comments.fetch();
    return this.$el.html(this.template());
  },
  events: {
    "click button": "newComment"
  },
  reset: function() {
    this.$el.find(".comments-list").html('');
    return this.addAll();
  },
  fetch: function() {
    return this.comments.fetch();
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
    return this.parent_view.incrementCommentCount();
  }
});
