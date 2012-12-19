Rfpez.Backbone.BidView = Backbone.View.extend
  tagName: "tbody"

  template: _.template """
  <tr class="main-bid">
    <td>as</td>
  </tr>
  <tr>
    <td class="bid-details-wrapper" colspan="5">
      <div class="collapse">
        <div class="bid-details row-fluid">
          <div class="span6">
            <strong>Body</strong>
            <p><%= body %></p>

            <strong>General Application</strong>
            <p>Placeholder</p>
          </div>
          <div class="span5 offset1">
            <strong>All comments about <%= vendor.name %></strong>
            <div class="comments-wrapper"></div>

            <hr />
            <strong>Projects applied for</strong>
            <div><%= vendor.titles_of_projects_applied_for %></div>
          </div>
        </div>
      </div>
    </td>
  </tr>
  """

  main_bid_template: _.template """
    <td>
      <% if (read == 1) { %>
        <a class="btn btn-small btn-circle toggle-read">&nbsp;</a>
      <% } else { %>
        <a class="btn btn-small btn-primary btn-circle toggle-read">&nbsp;</a>
      <% } %>
      <% if (anyone_read == 1) { %>
        <span class="anyone-read">R</span>
      <% } %>
    </td>
    <td>
      <a class="vendor-name toggle-details">
        <%= vendor.name %>
        &nbsp;
        <% if (awarded_at) { %>
          <span class="label label-success">Hired</span>
        <% } %>
        <% if (dismissed_at) { %>
          <span class="label label-important">Spam</span>
        <% } %>
      </a>
    </td>
    <td><%= total_score %></td>
    <td class="comment-count"><%= vendor.total_comments %></td>
    <td>
      <div class="btn-group">

        <% if (starred == 1) { %>
          <a class="btn btn-mini btn-primary unstar-button toggle-starred"><i class="icon-thumbs-up"></i></a>
        <% } else { %>
          <a class="btn btn-mini unstar-button toggle-starred"><i class="icon-thumbs-up"></i></a>
        <% } %>

        <% if (starred != 1 && thumbs_downed != 1) { %>
          <a class="btn btn-mini btn-primary toggle-no-vote"><i class="icon-thumbs-sideways"></i></a>
        <% } else { %>
          <a class="btn btn-mini toggle-no-vote"><i class="icon-thumbs-sideways"></i></a>
        <% } %>

        <% if (thumbs_downed == 1) { %>
          <a class="btn btn-mini btn-primary toggle-thumbs-down"><i class="icon-thumbs-down"></i></a>
        <% } else { %>
          <a class="btn btn-mini toggle-thumbs-down"><i class="icon-thumbs-down"></i></a>
        <% } %>


      </div>

      &nbsp;

      <div class="btn-group">
        <a class="btn dropdown-toggle btn-mini" data-toggle="dropdown" href="#">
          More
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <% if (awarded_at) { %>
            <li class="active"><a class="toggle-awarded">hired!</a></li>
          <% } else { %>
            <li><a class="toggle-awarded">hire me</a></li>
          <% } %>

          <% if (dismissed_at) { %>
            <li class="active"><a class="toggle-dismissed">spam</a></a>
          <% } else { %>
            <li><a class="toggle-dismissed">spam</a></li>
          <% } %>
        </ul>
      </div>

    </td>
  """

  events:
    "click .toggle-read": "toggleRead"
    "click .toggle-starred": "toggleStarred"
    "click .toggle-dismissed": "toggleDismissed"
    "click .toggle-awarded": "toggleAwarded"
    "click .toggle-details": "toggleDetails"
    "click .toggle-thumbs-down": "toggleThumbsDown"
    "click .toggle-no-vote": "toggleNoVote"

  initialize: ->
    @model.bind "create", @render, @
    @model.bind "change", @renderMainBid, @
    @model.bind "destroy", @remove, @

  render: ->
    @comments = false
    detailsOpen = if @$el.find(".bid-details-wrapper .collapse").hasClass('in') then true else false
    @$el.html @template(@model.toJSON())
    @renderMainBid()
    if detailsOpen then @$el.find(".bid-details-wrapper .collapse").addClass('in')
    return @

  renderMainBid: ->
    @$el.find(".main-bid").html @main_bid_template(@model.toJSON())

  toggleRead: ->
    params =
      read: if @model.attributes.read is "1" then "0" else "1"

    if params.read is "1" and @model.attributes.anyone_read is "0" then params["anyone_read"] = true

    @model.save params

  toggleDismissed: ->
    @model.save
      dismissed_at: if @model.attributes.dismissed_at then false else true

  toggleStarred: (save = true) ->
    attributes = {}

    if @model.attributes.starred is "1"
      attributes["starred"] = false
      @model.attributes.total_stars--
    else
      attributes["starred"] = true
      @model.attributes.total_stars++
      if @model.attributes.thumbs_downed is "1" then @toggleThumbsDown(false)

    @calculateTotalScore()

    if save
      @model.save attributes
    else
      @model.set attributes

  toggleThumbsDown: (save = true) ->
    attributes = {}

    if @model.attributes.thumbs_downed is "1"
      attributes["thumbs_downed"] = false
      @model.attributes.total_thumbs_down--
    else
      attributes["thumbs_downed"] = true
      @model.attributes.total_thumbs_down++
      if @model.attributes.starred is "1" then @toggleStarred(false)

    @calculateTotalScore()

    if save
      @model.save attributes
    else
      @model.set attributes

  toggleAwarded: ->
    @model.save
      awarded_at: if @model.attributes.awarded_at then false else true

  toggleNoVote: ->
    @$el.find(".toggle-starred.btn-primary").click()
    @$el.find(".toggle-thumbs-down.btn-primary").click()

  toggleDetails: ->
    if @model.attributes.read isnt "1" and !@$el.find(".bid-details-wrapper .collapse").hasClass('in') then @toggleRead()
    @$el.find(".bid-details-wrapper .collapse").collapse('toggle')

    if !@comments
      @comments = new Rfpez.Backbone.BidCommentsView
        parent_view: @
        vendor_id: @model.attributes.vendor_id

      @$el.find(".comments-wrapper").html(@comments.el)

  calculateTotalScore: ->
    @model.attributes.total_score = @model.attributes.total_stars - @model.attributes.total_thumbs_down

  incrementCommentCount: ->
    @model.attributes.vendor.total_comments++
    @renderMainBid()

  decrementCommentCount: ->
    @model.attributes.vendor.total_comments--
    @renderMainBid()
