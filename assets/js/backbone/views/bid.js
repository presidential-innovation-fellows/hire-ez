
Rfpez.Backbone.BidView = Backbone.View.extend({
  tagName: "tbody",
  template: _.template("<tr class=\"main-bid\">\n  <td>as</td>\n</tr>\n<tr>\n  <td class=\"bid-details-wrapper\" colspan=\"7\">\n    <div class=\"collapse\">\n      <div class=\"bid-details row-fluid\">\n        <div class=\"span6\">\n          <strong>Body</strong>\n          <p><%= body %></p>\n\n          <strong>General Application</strong>\n          <p>Placeholder</p>\n        </div>\n        <div class=\"span5 offset1\">\n          <strong>Comments</strong>\n          <div class=\"comments-wrapper\"></div>\n        </div>\n      </div>\n    </div>\n  </td>\n</tr>"),
  main_bid_template: _.template("<td>\n  <% if (read == 1) { %>\n    <a class=\"btn btn-small btn-circle toggle-read\">&nbsp;</a>\n  <% } else { %>\n    <a class=\"btn btn-small btn-primary btn-circle toggle-read\">&nbsp;</a>\n  <% } %>\n  <% if (anyone_read == 1) { %>\n    <span class=\"anyone-read\">R</span>\n  <% } %>\n</td>\n<td><a class=\"vendor-name toggle-details\"><%= vendor.name %></a></td>\n<td><%= total_stars %></td>\n<td class=\"comment-count\"><%= total_comments %></td>\n<td>\n  <% if (starred == 1) { %>\n    <a class=\"btn btn-mini btn-primary unstar-button toggle-starred\"><i class=\"icon-thumbs-up\"></i></a>\n  <% } else { %>\n    <a class=\"btn btn-mini unstar-button toggle-starred\"><i class=\"icon-thumbs-up\"></i></a>\n  <% } %>\n</td>\n<td>\n  <% if (dismissed_at) { %>\n    <a class=\"btn btn-mini btn-primary unstar-button toggle-dismissed\"><i class=\"icon-trash\"></i></a>\n  <% } else { %>\n    <a class=\"btn btn-mini unstar-button toggle-dismissed\"><i class=\"icon-trash\"></i></a>\n  <% } %>\n</td>\n<td>\n  <% if (awarded_at) { %>\n  <a class=\"btn btn-mini award-button btn-primary toggle-awarded\">hired!</a>\n  <% } else { %>\n  <a class=\"btn btn-mini award-button toggle-awarded\">hire me!</a>\n  <% } %>\n</td>"),
  events: {
    "click .toggle-read": "toggleRead",
    "click .toggle-starred": "toggleStarred",
    "click .toggle-dismissed": "toggleDismissed",
    "click .toggle-awarded": "toggleAwarded",
    "click .toggle-details": "toggleDetails"
  },
  initialize: function() {
    this.model.bind("create", this.render, this);
    this.model.bind("change", this.renderMainBid, this);
    return this.model.bind("destroy", this.remove, this);
  },
  render: function() {
    var detailsOpen;
    this.comments = false;
    detailsOpen = this.$el.find(".bid-details-wrapper .collapse").hasClass('in') ? true : false;
    this.$el.html(this.template(this.model.toJSON()));
    this.renderMainBid();
    if (detailsOpen) {
      this.$el.find(".bid-details-wrapper .collapse").addClass('in');
    }
    return this;
  },
  renderMainBid: function() {
    return this.$el.find(".main-bid").html(this.main_bid_template(this.model.toJSON()));
  },
  toggleRead: function() {
    var params;
    params = {
      read: this.model.attributes.read === "1" ? "0" : "1"
    };
    if (params.read === "1" && this.model.attributes.anyone_read === "0") {
      params["anyone_read"] = true;
    }
    return this.model.save(params);
  },
  toggleDismissed: function() {
    return this.model.save({
      dismissed_at: this.model.attributes.dismissed_at ? false : true
    });
  },
  toggleStarred: function() {
    var attributes;
    attributes = {};
    if (this.model.attributes.starred === "1") {
      attributes["starred"] = false;
      this.model.attributes.total_stars--;
    } else {
      attributes["starred"] = true;
      this.model.attributes.total_stars++;
    }
    return this.model.save(attributes);
  },
  toggleAwarded: function() {
    return this.model.save({
      awarded_at: this.model.attributes.awarded_at ? false : true
    });
  },
  toggleDetails: function() {
    if (this.model.attributes.read !== "1" && !this.$el.find(".bid-details-wrapper .collapse").hasClass('in')) {
      this.toggleRead();
    }
    this.$el.find(".bid-details-wrapper .collapse").collapse('toggle');
    if (!this.comments) {
      this.comments = new Rfpez.Backbone.BidCommentsView({
        parent_view: this,
        bid_id: this.model.attributes.id
      });
      return this.$el.find(".comments-wrapper").html(this.comments.el);
    }
  },
  incrementCommentCount: function() {
    this.model.attributes.total_comments++;
    return this.renderMainBid();
  },
  decrementCommentCount: function() {
    this.model.attributes.total_comments--;
    return this.renderMainBid();
  }
});
