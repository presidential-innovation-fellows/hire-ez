Rfpez.Backbone.ProjectComment = Backbone.Model.extend
  validate: (attrs) ->
    if !attrs.body
      return true

  defaults: ->
    owner: false
    commentable_type: "project"

  clear: ->
    @destroy()
