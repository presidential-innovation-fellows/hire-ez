Rfpez.Backbone.BidComment = Backbone.Model.extend
  validate: (attrs) ->
    if !attrs.body
      return true

  defaults: ->
    owner: false
    commentable_type: "bid"

  clear: ->
    @destroy()
