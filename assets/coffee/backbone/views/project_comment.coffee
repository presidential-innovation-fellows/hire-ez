Rfpez.Backbone.ProjectCommentView = Backbone.View.extend
  tagName: "div"
  className: "well comment"

  template: _.template """
    <div class="body">
      <span class="author">
        <%= officer.name %>
      </span>
      <%= body %>
    </div>
    <span class="timestamp">
      <span class="posted-at">Posted <span class="timeago" title="<%= formatted_created_at %>"></span></span>
    </span>
    <a class="delete-comment only-user only-user-<%= officer.user_id %>">Delete</a>
  """

  events:
    "click a.delete-comment": "clear"

  initialize: ->
    @model.bind "change", @render, @
    @model.bind "destroy", @remove, @

  render: ->
    @$el.html @template(@model.toJSON())
    @$el.find(".timeago").timeago()
    return @

  clear: ->
    @model.clear()
