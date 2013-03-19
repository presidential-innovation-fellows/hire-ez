Rfpez.Backbone.AdminOfficerView = Backbone.View.extend
  tagName: "tr"

  template: _.template """
    <td><%- id %></td>
    <td><%- name %> - <%- title %></td>
    <td><%- user.email %></td>
    <td>
      <div class="not-user-<%- user.id %>">
        <% if (role == 3 && !isSuperAdmin) { %>
          This officer is a super-admin.
        <% } else { %>
          <select class="user_role_select">
            <option value="0" <% if(role == 0){ %>selected <% } %>>Program Officer</option>
            <option value="1" <% if(role == 1){ %>selected <% } %>>Contracting Officer</option>
            <option value="2" <% if(role == 2){ %>selected <% } %>>Admin</option>
            <% if (isSuperAdmin) { %>
              <option value="3" <% if(role == 3){ %>selected <% } %>>Super Admin</option>
            <% } %>
          </select>
        <% } %>
      </div>
      <div class="only-user only-user-<%- user.id %>">
        You're a <%- role_text %>.
      </div>
    </td>
    <td>
      <% if (role != 3){ %>
        <div class="super-admin-only">
          <div class="not-user-<%- user.id %>">
            <% if (!user.banned_at){ %>
              <a class="btn btn-danger ban-button btn-mini">Ban Officer</a>
            <% } else { %>
              <a class="btn unban-button btn-mini">Un-Ban Officer</a>
            <% } %>
              <a class="btn resetpw-button btn-mini">Reset Password</a>
          </div>
        </div>
      <% } %>
    </td>
  """

  events:
    "change .user_role_select": "update"
    "click .ban-button": "ban"
    "click .unban-button": "unban"
    "click .resetpw-button": "resetpw"

  initialize: ->
    @model.bind "change", @render, @
    @model.bind "destroy", @remove, @

  render: ->
    @$el.html @template(_.extend(@model.toJSON(), {isSuperAdmin: Rfpez.Backbone.Officers.isSuperAdmin}))
    return @

  ban: ->
    if confirm('Are you sure you want to ban this officer? This could have unintended consequences if they were the only officer on a project.')
      @model.save
        command: "ban"

  unban: ->
    @model.save
      command: "unban"

  resetpw: ->
    @model.save {command: "resetpw"}, {success: (model, res) ->
      alert "Send this link to the officer: \r\n" + res.reset_link}

  update: ->
    @model.save
      role: @$el.find(".user_role_select").val()

  clear: ->
    @model.clear()

