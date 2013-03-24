
Rfpez.Backbone.CollaboratorList = Backbone.Collection.extend({
  existing_emails: function() {
    return this.map(function(c) {
      return c.attributes.user.email.toLowerCase();
    });
  },
  model: Rfpez.Backbone.Collaborator
});
