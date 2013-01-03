Rfpez.Backbone.BidView = Backbone.View.extend
  tagName: "tbody"
  className: "bid"

  template: _.template """
  <tr class="main-bid">
    <td>as</td>
  </tr>
  <tr>
    <td class="bid-details-wrapper" colspan="5">
      <div class="collapse">
        <div class="bid-details row-fluid">
          <div class="span8">
            <div class="bid-tab-wrapper">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#body<%- id %>" data-toggle="tab">Body</a></li>
                <li><a href="#general_paragraph<%- id %>" data-toggle="tab">General Paragraph</a></li>
                <li><a href="#resume<%- id %>" data-toggle="tab">Resume</a></li>
                <li><a href="#links<%- id %>" data-toggle="tab">Links & Contact Info</a></li>
              </ul>

              <div class="tab-content">
                <div class="tab-pane active" id="body<%- id %>">
                  <%= _.escape(body).replace(new RegExp('\\r?\\n', 'g'), '<br />') %>
                </div>
                <div class="tab-pane" id="general_paragraph<%- id %>"><%- vendor.general_paragraph %></div>
                <div class="tab-pane" id="resume<%- id %>"><%= vendor.resume_safe %></div>
                <div class="tab-pane" id="links<%- id %>">
                  <dl>
                    <% if (vendor.link_1){ %>
                      <dt>Link #1</dt>
                      <dd><a href="<%- vendor.link_1 %>" target="_blank"><%- vendor.link_1 %></a></dd>
                    <% } %>
                    <% if (vendor.link_2){ %>
                      <dt>Link #2</dt>
                      <dd><a href="<%- vendor.link_2 %>" target="_blank"><%- vendor.link_2 %></a></dd>
                    <% } %>
                    <% if (vendor.link_3){ %>
                      <dt>Link #3</dt>
                      <dd><a href="<%- vendor.link_3 %>" target="_blank"><%- vendor.link_3 %></a></dd>
                    <% } %>

                    <dt>Location</dt>
                    <dd><a href="https://maps.google.com/?q=<%- vendor.location %>" target="_blank"><%- vendor.location %></a></dd>

                    <dt>Email</dt>
                    <dd><a href="mailto:<%- vendor.email %>"><%- vendor.email %></a></dd>

                    <dt>Phone</dt>
                    <dd><%- vendor.phone %></dd>
                  </dl>
                </div>
              </div>
            </div>
          </div>
          <div class="span4">
            <div class="transfer-bid-wrapper"></div>
            <div class="comments-wrapper"></div>
          </div>
        </div>
      </div>
    </td>
  </tr>
  """

  transfer_bid_template: _.template """
    <strong>Applied to:</strong>
    <p><%- vendor.titles_of_projects_applied_for.join(", ") %></p>

    <form action="/projects/<%- project_id %>/bids/<%- id %>/transfer" method="POST">
      <strong>Refer to:</strong>
      <select class="select-inline" name="project_id">
        <% _.each(vendor.projects_not_applied_for, function(project){ %>
          <option value="<%- project.id %>"><%- project.title %></option>
        <% }); %>
      </select>
      <button class="btn btn-small">Send!</button>
    </form>
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
      <a class="vendor-name toggle-details" href="/projects/<%- project_id %>/bids/<%- id %>">
        <%- vendor.name %>
        &nbsp;
        <% if (awarded_at) { %>
          <span class="label label-success">Hired</span>
        <% } %>
        <% if (dismissed_at) { %>
          <span class="label label-important">Spam</span>
        <% } %>
      </a>
    </td>
    <td><%- total_score %></td>
    <td class="comment-count"><%- vendor.total_comments %></td>
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

    @expanded = @options.expanded

  render: ->
    @comments = false
    detailsOpen = if @$el.find(".bid-details-wrapper .collapse").hasClass('in') then true else false
    @$el.html @template(@model.toJSON())
    @renderMainBid()
    if detailsOpen then @$el.find(".bid-details-wrapper .collapse").addClass('in')

    if @expanded
      @renderComments()
      @renderTransferBid()
      @$el.find(".collapse").addClass('in')

    return @

  renderMainBid: ->
    @$el.find(".main-bid").html @main_bid_template(@model.toJSON())

  renderComments: ->
    @comments = new Rfpez.Backbone.BidCommentsView
      parent_view: @
      vendor_id: @model.attributes.vendor_id

    @$el.find(".comments-wrapper").html(@comments.el)

  renderTransferBid: ->
    @$el.find(".transfer-bid-wrapper").html @transfer_bid_template(@model.toJSON())

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

  toggleDetails: (e) ->
    if e.metaKey
      return
    else
      e.preventDefault()

    if @model.attributes.read isnt "1" and !@$el.find(".bid-details-wrapper .collapse").hasClass('in') then @toggleRead()
    @$el.find(".bid-details-wrapper .collapse").collapse('toggle')

    if !@comments then @renderComments()

    if @$el.find(".transfer-bid-wrapper div").length is 0
      @model.fetchDetails( => @renderTransferBid() )

  calculateTotalScore: ->
    @model.attributes.total_score = @model.attributes.total_stars - @model.attributes.total_thumbs_down

  incrementCommentCount: ->
    @model.attributes.vendor.total_comments++
    @renderMainBid()

  decrementCommentCount: ->
    @model.attributes.vendor.total_comments--
    @renderMainBid()
