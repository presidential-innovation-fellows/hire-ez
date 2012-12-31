Rfpez.Backbone.StreamPage = Backbone.View.extend

  initialize: ->
    Rfpez.Backbone.ProjectComments = new Rfpez.Backbone.ProjectCommentList
    Rfpez.Backbone.ProjectComments.bind 'add', @addOne, @
    Rfpez.Backbone.ProjectComments.bind 'reset', @reset, @
    Rfpez.Backbone.ProjectComments.bind 'all', @render, @
    @bind 'errorAdding', @showError


    Rfpez.Backbone.ProjectComments.url = "/projects/#{@options.project_id}/comments"
    Rfpez.Backbone.ProjectComments.project_id = @options.project_id

    Rfpez.Backbone.ProjectComments.reset(@options.bootstrap)

  showError: (errors) ->
    alert errors[0]

  reset: ->
    $(".comments-list").html('')
    @addAll()

  render: ->
    #

  addOne: (model) ->
    if model.attributes.notification_type
      view = new Rfpez.Backbone.NotificationView({model: model})
    else
      view = new Rfpez.Backbone.CommentView({model: model})
    $comment = $(view.render().el)
    $comment.addClass('well') if !model.attributes.notification_type
    $(".comments-list").append($comment);

  addAll: ->
    Rfpez.Backbone.ProjectComments.each @addOne

$(document).on "submit", "#add-comment-form", (e) ->
  e.preventDefault()
  dateString = new Date().toISOString()

  Rfpez.Backbone.ProjectComments.create
    officer:
      name: $(@).data('officer-name')
      user_id: $(@).data('officer-user-id')
    body: $(@).find("textarea").val()
    formatted_created_at: dateString
    commentable_id: Rfpez.Backbone.ProjectComments.project_id
  ,
    error: (obj, err) ->
      obj.clear()

  $(@).resetForm()
