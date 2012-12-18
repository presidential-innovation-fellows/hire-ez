Rfpez.Backbone.NotificationView = Backbone.View.extend
  tagName: "div"
  className: "notification"

  template: _.template """
    <i class="<%= js_parsed.icon %>"></i>
    <%= js_parsed.text %>
    <div class="date"><span class="timeago" title="<%= formatted_created_at %>"></span></div>
  """
  parse: ->
    if @model.attributes.notification_type is "Dismissal"
      text = """ <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.name}'s</a> bid was dismissed. """
      icon = "icon-thumbs-down"
    else if @model.attributes.notification_type is "Undismissal"
      text = """ <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.name}'s</a> bid was un-dismissed. """
      icon = "icon-repeat"
    else if @model.attributes.notification_type is "BidSubmit"
      text = """ <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.name}</a> submitted a bid. """
      icon = "icon-list-alt"
    else if @model.attributes.notification_type is "Award"
      text = """ The Contract was awarded to <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.name}</a>. """
      icon = "icon-thumbs-up"
    else if @model.attributes.notification_type is "ProjectCollaboratorAdded"
      text = """ #{@model.attributes.payload.officer.user.email} was added as a collaborator. """
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
