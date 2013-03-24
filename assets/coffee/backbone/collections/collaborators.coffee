Rfpez.Backbone.CollaboratorList = Backbone.Collection.extend
  existing_emails: ->
    @.map (c) ->
      return c.attributes.user.email.toLowerCase()

  model: Rfpez.Backbone.Collaborator
