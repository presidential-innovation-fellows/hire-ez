Rfpez.Backbone.Bid = Backbone.Model.extend
  validate: (attrs) ->
    errors = []

    if errors.length > 0
      return errors

  defaults: ->
    thumbs_downed: false
    starred: false
    interview: false

  clear: ->
    @destroy()

  fetchDetails: (cb) ->
    $.getJSON @url(), (data) =>
      @attributes.vendor = data.vendor
      @change()
      cb()
