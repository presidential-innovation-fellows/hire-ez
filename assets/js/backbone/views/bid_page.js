
Rfpez.Backbone.BidPage = Backbone.View.extend({
  initialize: function() {
    Rfpez.Backbone.Bids = new Rfpez.Backbone.BidList();
    Rfpez.Backbone.Bids.bind('reset', this.reset, this);
    Rfpez.Backbone.Bids.bind('all', this.render, this);
    this.expanded = this.options.expanded;
    Rfpez.Backbone.Bids.url = "/projects/" + this.options.project_id + "/bids";
    return Rfpez.Backbone.Bids.reset(this.options.bootstrap);
  },
  reset: function() {
    $("#bids-table tbody").after('');
    return this.addAll();
  },
  render: function() {},
  addOne: function(bid) {
    var html, view;
    view = new Rfpez.Backbone.BidView({
      model: bid,
      expanded: this.expanded
    });
    html = view.render().el;
    return $("#bids-table").append(html);
  },
  addAll: function() {
    return Rfpez.Backbone.Bids.each(this.addOne, this);
  }
});
