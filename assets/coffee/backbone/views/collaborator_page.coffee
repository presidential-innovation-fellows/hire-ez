Rfpez.Backbone.CollaboratorPage = Backbone.View.extend

  initialize: ->
    Rfpez.Backbone.Collaborators = new Rfpez.Backbone.CollaboratorList()
    Rfpez.Backbone.Collaborators.bind 'add', @addOne, @
    Rfpez.Backbone.Collaborators.bind 'reset', @reset, @
    Rfpez.Backbone.Collaborators.bind 'all', @render, @
    @bind 'errorAdding', @showError

    Rfpez.Backbone.Collaborators.owner_id = @options.owner_id
    Rfpez.Backbone.Collaborators.isSuperAdmin = $("body").hasClass('super-admin')
    Rfpez.Backbone.Collaborators.reset(@options.bootstrap)
    Rfpez.Backbone.Collaborators.url = "/projects/#{@options.project_id}/collaborators"

  showError: (errors) ->
    $("#add-collaborator-form button").flash_button_message("warning", errors[0])

  reset: ->
    $("#collaborators-tbody").html('')
    @addAll()

  render: ->
    #

  addOne: (collaborator) ->
    view = new Rfpez.Backbone.CollaboratorView({model: collaborator})
    html = view.render().el
    $("#collaborators-tbody").append(html);

  addAll: ->
    Rfpez.Backbone.Collaborators.each @addOne

$(document).on "submit", "#add-collaborator-form", (e) ->
  e.preventDefault()
  email = $("#add-collaborator-form input[name=email]").val()
  $("#add-collaborator-form input[name=email]").val('')

  Rfpez.Backbone.Collaborators.create
    user:
      email: email
    pivot:
      owner: 0
  ,
    error: (obj, err) ->
      obj.clear()