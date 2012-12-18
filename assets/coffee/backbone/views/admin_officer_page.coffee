Rfpez.Backbone.AdminOfficerPage = Backbone.View.extend

  initialize: ->
    Rfpez.Backbone.Officers = new Rfpez.Backbone.OfficerList()
    Rfpez.Backbone.Officers.bind 'reset', @reset, @
    Rfpez.Backbone.Officers.bind 'all', @render, @

    Rfpez.Backbone.Officers.isSuperAdmin = $("body").hasClass('super-admin')
    Rfpez.Backbone.Officers.reset(@options.bootstrap)

  reset: ->
    $("#officers-tbody").html('')
    @addAll()

  render: ->
    #

  addOne: (officer) ->
    view = new Rfpez.Backbone.AdminOfficerView({model: officer})
    html = view.render().el
    $("#officers-tbody").append(html);

  addAll: ->
    Rfpez.Backbone.Officers.each @addOne
