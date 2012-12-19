Rfpez.Backbone.BidCommentsView = Backbone.View.extend

  tagName: "div"
  className: "comments-list-wrapper"

  template: _.template """

    <div class="comments-list"></div>
    <div class="comments-placeholder centered"><img src="/img/spinner.gif" /></div>

    <textarea></textarea>
    <button class="btn btn-primary btn-small">Add Comment</button>
  """

  initialize: ->
    @comments = new Rfpez.Backbone.BidCommentList()
    @comments.bind 'reset', @reset, @
    @comments.bind 'add', @addOne, @

    @parent_view = @options.parent_view

    @comments.url = "/vendors/#{@options.vendor_id}/comments"

    @$el.html @template()

    @comments.fetch()

  events:
    "click button": "newComment"

  reset: ->
    @$el.find(".comments-list").html('')
    @addAll()

  addOne: (comment) ->
    view = new Rfpez.Backbone.BidCommentView({model: comment, parent_view: @})
    html = view.render().el
    @$el.find(".comments-list").append(html)

  addAll: ->
    @$el.find(".comments-placeholder").hide()
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