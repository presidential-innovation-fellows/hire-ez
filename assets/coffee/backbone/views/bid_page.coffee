Rfpez.Backbone.BidPage = Backbone.View.extend

  initialize: ->
    Rfpez.Backbone.Bids = new Rfpez.Backbone.BidList()
    Rfpez.Backbone.Bids.bind 'reset', @reset, @
    Rfpez.Backbone.Bids.bind 'all', @render, @

    Rfpez.Backbone.Bids.url = "/projects/#{@options.project_id}/bids"
    Rfpez.Backbone.Bids.reset(@options.bootstrap)

  reset: ->
    $("#bids-table tbody").after('')
    @addAll()

  render: ->
    #

  addOne: (bid) ->
    view = new Rfpez.Backbone.BidView({model: bid})
    html = view.render().el
    $("#bids-table").append(html);

  addAll: ->
    Rfpez.Backbone.Bids.each @addOne
