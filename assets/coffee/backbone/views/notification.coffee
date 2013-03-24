Rfpez.Backbone.NotificationView = Backbone.View.extend
  tagName: "div"
  className: "notification"

  template: _.template """
    <i class="<%- js_parsed.icon %>"></i>
    <%= js_parsed.text %>
    <div class="date"><span class="timeago" title="<%- formatted_created_at %>"></span></div>
  """
  parse: ->
    text = @model.attributes.parsed.line1

    if @model.attributes.notification_type is "Dismissal"
      icon = "icon-thumbs-down"
    else if @model.attributes.notification_type is "Undismissal"
      icon = "icon-repeat"
    else if @model.attributes.notification_type is "BidSubmit"
      icon = "icon-list-alt"
    else if @model.attributes.notification_type is "Award"
      icon = "icon-thumbs-up"
    else if @model.attributes.notification_type is "ProjectCollaboratorAdded"
      icon = "icon-user"

    return {
      text: if text? then text else @model.attributes.notification_type
      icon: if icon? then icon else "icon-arrow-right"
    }

  # events:

  initialize: ->
    @model.bind "change", @render, @
    @model.bind "destroy", @remove, @

  render: ->
    @$el.html @template(_.extend(@model.toJSON(), js_parsed: @parse()))
    return @

  clear: ->
    @model.clear()
