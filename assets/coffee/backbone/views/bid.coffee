Rfpez.Backbone.BidView = Backbone.View.extend
  tagName: "tbody"

  template: _.template """
  <tr class="main-bid">
    <td>as</td>
  </tr>
  <tr>
    <td class="bid-details-wrapper" colspan="7">
      <div class="collapse">
        <div class="bid-details row-fluid">
          <div class="span8 well">
            <strong>Project Statement</strong>
            <p><%= body %></p>
            <strong>General Statement</strong>
            <p><%= body %></p>
            <strong>Resume</strong>
            <p><%= resume %></p>
          </div>
          <div class="span3 bid-sidebar" >

            <table>
              <tr>
                <td>
                  <div class="btn-group">

                      <a class="btn btn-mini unstar-button toggle-starred"><i class="icon-thumbs-up"></i></a>

                      <a class="btn btn-mini btn-primary toggle-no-vote">&nbsp;&nbsp;&nbsp;&nbsp;</a>

                      <a class="btn btn-mini toggle-thumbs-down"><i class="icon-thumbs-down"></i></a>

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
              </tr>
             </table>

            <div class="projects-applied">
              <strong>Applied to:</strong>
              <p>RFP-EZr, Periwinkle Button</p>
            </div>

            <div class="comments-wrapper"></div>

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
    <td><a class="vendor-name toggle-details"><%= vendor.name %></a></td>
    <td><%= total_stars %></td>
    <td class="comment-count"><%= total_comments %></td>
  """

  events:
    "click .toggle-read": "toggleRead"
    "click .toggle-starred": "toggleStarred"
    "click .toggle-dismissed": "toggleDismissed"
    "click .toggle-awarded": "toggleAwarded"
    "click .toggle-details": "toggleDetails"

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

  toggleStarred: ->
    attributes = {}

    if @model.attributes.starred is "1"
      attributes["starred"] = false
      @model.attributes.total_stars--
    else
      attributes["starred"] = true
      @model.attributes.total_stars++

    @model.save attributes

  toggleAwarded: ->
    @model.save
      awarded_at: if @model.attributes.awarded_at then false else true

  toggleDetails: ->
    if @model.attributes.read isnt "1" and !@$el.find(".bid-details-wrapper .collapse").hasClass('in') then @toggleRead()
    @$el.find(".bid-details-wrapper .collapse").collapse('toggle')

    if !@comments
      @comments = new Rfpez.Backbone.BidCommentsView
        parent_view: @
        bid_id: @model.attributes.id

      @$el.find(".comments-wrapper").html(@comments.el)

  incrementCommentCount: ->
    @model.attributes.total_comments++
    @renderMainBid()

  decrementCommentCount: ->
    @model.attributes.total_comments--
    @renderMainBid()
