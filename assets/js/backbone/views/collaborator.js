
Rfpez.Backbone.CollaboratorView = Backbone.View.extend({
  tagName: "tr",
  template: _.template("<td class=\"email\"><%- user.email %></td>\n<td>\n  <% if (pivot.owner === \"1\") { %>\n    <i class=\"icon-star\"></i>\n  <% } %>\n</td>\n<td>\n  <% if (!isSuperAdmin) { %>\n    <span class=\"not-user-<%- user.id %> only-user only-user-<%- owner_id %>\">\n  <% } else { %>\n    <span class=\"not-user-<%- user.id %>\">\n  <% } %>\n    <% if (pivot.owner !== \"1\") { %>\n      <button class=\"btn btn-danger\">Remove</button>\n    <% } else { %>\n      Can't remove the owner.\n    <% } %>\n  </span>\n  <span class=\"only-user only-user-<%- user.id %>\">\n    That's you!\n  </span>\n</td>"),
  events: {
    "click .btn.btn-danger": "clear"
  },
  initialize: function() {
    this.model.bind("change", this.render, this);
    this.model.bind("destroy", this.remove, this);
    return this.owner_id = this.options.owner_id;
  },
  render: function() {
    this.$el.html(this.template(_.extend(this.model.toJSON(), {
      owner_id: Rfpez.Backbone.Collaborators.owner_id,
      isSuperAdmin: Rfpez.Backbone.Collaborators.isSuperAdmin
    })));
    return this;
  },
  clear: function() {
    return this.model.clear();
  }
});
