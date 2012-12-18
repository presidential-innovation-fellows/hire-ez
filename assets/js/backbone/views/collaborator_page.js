
Rfpez.Backbone.CollaboratorPage = Backbone.View.extend({
  initialize: function() {
    Rfpez.Backbone.Collaborators = new Rfpez.Backbone.CollaboratorList();
    Rfpez.Backbone.Collaborators.bind('add', this.addOne, this);
    Rfpez.Backbone.Collaborators.bind('reset', this.reset, this);
    Rfpez.Backbone.Collaborators.bind('all', this.render, this);
    this.bind('errorAdding', this.showError);
    Rfpez.Backbone.Collaborators.owner_id = this.options.owner_id;
    Rfpez.Backbone.Collaborators.reset(this.options.bootstrap);
    return Rfpez.Backbone.Collaborators.url = "/projects/" + this.options.project_id + "/collaborators";
  },
  showError: function(errors) {
    return $("#add-collaborator-form button").flash_button_message("warning", errors[0]);
  },
  reset: function() {
    $("#collaborators-tbody").html('');
    return this.addAll();
  },
  render: function() {},
  addOne: function(collaborator) {
    var html, view;
    view = new Rfpez.Backbone.CollaboratorView({
      model: collaborator
    });
    html = view.render().el;
    return $("#collaborators-tbody").append(html);
  },
  addAll: function() {
    return Rfpez.Backbone.Collaborators.each(this.addOne);
  }
});

$(document).on("submit", "#add-collaborator-form", function(e) {
  var email;
  e.preventDefault();
  email = $("#add-collaborator-form input[name=email]").val();
  $("#add-collaborator-form input[name=email]").val('');
  return Rfpez.Backbone.Collaborators.create({
    user: {
      email: email
    },
    pivot: {
      owner: 0
    }
  }, {
    error: function(obj, err) {
      return obj.clear();
    }
  });
});
