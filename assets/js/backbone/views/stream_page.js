
Rfpez.Backbone.StreamPage = Backbone.View.extend({
  initialize: function() {
    Rfpez.Backbone.ProjectComments = new Rfpez.Backbone.ProjectCommentList;
    Rfpez.Backbone.ProjectComments.bind('add', this.addOne, this);
    Rfpez.Backbone.ProjectComments.bind('reset', this.reset, this);
    Rfpez.Backbone.ProjectComments.bind('all', this.render, this);
    this.bind('errorAdding', this.showError);
    Rfpez.Backbone.ProjectComments.url = "/projects/" + this.options.project_id + "/comments";
    Rfpez.Backbone.ProjectComments.project_id = this.options.project_id;
    return Rfpez.Backbone.ProjectComments.reset(this.options.bootstrap);
  },
  showError: function(errors) {
    return alert(errors[0]);
  },
  reset: function() {
    $(".comments-list").html('');
    return this.addAll();
  },
  render: function() {},
  addOne: function(model) {
    var $comment, view;
    if (model.attributes.notification_type) {
      view = new Rfpez.Backbone.NotificationView({
        model: model
      });
    } else {
      view = new Rfpez.Backbone.CommentView({
        model: model
      });
    }
    $comment = $(view.render().el);
    if (!model.attributes.notification_type) {
      $comment.addClass('well');
    }
    return $(".comments-list").append($comment);
  },
  addAll: function() {
    return Rfpez.Backbone.ProjectComments.each(this.addOne);
  }
});

$(document).on("submit", "#add-comment-form", function(e) {
  var dateString;
  e.preventDefault();
  dateString = new Date().toISOString();
  Rfpez.Backbone.ProjectComments.create({
    officer: {
      name: $(this).data('officer-name'),
      user_id: $(this).data('officer-user-id')
    },
    body: $(this).find("textarea").val(),
    formatted_created_at: dateString,
    commentable_id: Rfpez.Backbone.ProjectComments.project_id
  }, {
    error: function(obj, err) {
      return obj.clear();
    }
  });
  return $(this).resetForm();
});
