
Rfpez.Backbone.Collaborator = Backbone.Model.extend({
  validate: function(attrs) {
    var errors;
    errors = [];
    if (!attrs.user.email) {
      return true;
    } else if (!attrs.user.email.match(/.gov|@si.edu$/i)) {
      errors.push("Sorry, .gov addresses only");
    } else if (!attrs.id && Rfpez.Backbone.Collaborators.existing_emails().indexOf(attrs.user.email.toLowerCase()) !== -1) {
      errors.push("That collaborator already exists.");
    }
    if (errors.length > 0) {
      alert(errors);
      return errors;
    }
  },
  defaults: function() {
    return {
      owner: false
    };
  },
  clear: function() {
    return this.destroy();
  }
});
