Rfpez.Backbone.CollaboratorView = Backbone.View.extend
  tagName: "tr"

  template: _.template """
    <td class="email"><%- user.email %></td>
    <td>
      <% if (pivot.owner === "1") { %>
        <i class="icon-star"></i>
      <% } %>
    </td>
    <td>
      <span class="not-user-<%- user.id %> only-user only-user-<%- owner_id %>">
        <% if (pivot.owner !== "1") { %>
          <button class="btn btn-danger">Remove</button>
        <% } else { %>
          Can't remove the owner.
        <% } %>
      </span>
      <span class="only-user only-user-<%- user.id %>">
        That's you!
      </span>
    </td>
  """

  events:
    "click .btn.btn-danger": "clear"

  initialize: ->
    @model.bind "change", @render, @
    @model.bind "destroy", @remove, @
    @owner_id = @options.owner_id

  render: ->
    @$el.html @template(_.extend(@model.toJSON(), {owner_id: Rfpez.Backbone.Collaborators.owner_id}))
    return @

  clear: ->
    @model.clear()
