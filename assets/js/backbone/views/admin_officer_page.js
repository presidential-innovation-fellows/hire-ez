
Rfpez.Backbone.AdminOfficerPage = Backbone.View.extend({
  initialize: function() {
    Rfpez.Backbone.Officers = new Rfpez.Backbone.OfficerList();
    Rfpez.Backbone.Officers.bind('reset', this.reset, this);
    Rfpez.Backbone.Officers.bind('all', this.render, this);
    Rfpez.Backbone.Officers.isSuperAdmin = $("body").hasClass('super-admin');
    return Rfpez.Backbone.Officers.reset(this.options.bootstrap);
  },
  reset: function() {
    $("#officers-tbody").html('');
    return this.addAll();
  },
  render: function() {},
  addOne: function(officer) {
    var html, view;
    view = new Rfpez.Backbone.AdminOfficerView({
      model: officer
    });
    html = view.render().el;
    return $("#officers-tbody").append(html);
  },
  addAll: function() {
    return Rfpez.Backbone.Officers.each(this.addOne);
  }
});
