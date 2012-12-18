Rfpez.Backbone.BidCommentsView = Backbone.View.extend

  tagName: "div"
  className: "comments-list-wrapper"

  template: _.template """ <div class="comments-list"></div>

    <textarea></textarea>
    <button class="btn btn-primary btn-small">Add Comment</button>
  """

  initialize: ->
    @comments = new Rfpez.Backbone.BidCommentList()
    @comments.bind 'reset', @reset, @
    @comments.bind 'add', @addOne, @

    @parent_view = @options.parent_view

    @comments.url = "/bids/#{@options.bid_id}/comments"

    @comments.fetch()

    @$el.html @template()

  events:
    "click button": "newComment"

  reset: ->
    @$el.find(".comments-list").html('')
    @addAll()

  fetch: ->
    @comments.fetch()

  addOne: (comment) ->
    view = new Rfpez.Backbone.BidCommentView({model: comment, parent_view: @})
    html = view.render().el
    @$el.find(".comments-list").append(html)

  addAll: ->
    @comments.each @addOne, @

  newComment: ->
    @comments.create
      officer:
        name: $("body").data('officer-name')
        user_id: $("body").data('officer-user-id')
      body: @$el.find("textarea").val()
      formatted_created_at: new Date().toISOString()

    @$el.find("textarea").val('')

    @parent_view.incrementCommentCount()