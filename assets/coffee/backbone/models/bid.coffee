Rfpez.Backbone.Bid = Backbone.Model.extend
  validate: (attrs) ->
    errors = []

    if errors.length > 0
      return errors

  # defaults: ->
  #   owner: false

  clear: ->
    @destroy()
