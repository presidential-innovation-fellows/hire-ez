
Rfpez.Backbone.BidView = Backbone.View.extend({
  tagName: "tbody",
  template: _.template("<tr class=\"main-bid\">\n  <td>as</td>\n</tr>\n<tr>\n  <td class=\"bid-details-wrapper\" colspan=\"7\">\n    <div class=\"collapse\">\n      <div class=\"bid-details row-fluid\">\n        <div class=\"span8 well\">\n          <div class=\"tabbable\"> <!-- Only required for left/right tabs -->\n            <ul class=\"nav nav-tabs\">\n              <li class=\"active\"><a href=\"#project-statement-<%= bid_id %>\" data-toggle=\"tab\">Project Statement</a></li>\n              <li><a href=\"#overall-statement-<%= bid_id %>\" data-toggle=\"tab\">Overall Statement</a></li>\n              <li><a href=\"#resume-<%= bid_id %>\" data-toggle=\"tab\">Résumé</a></li>\n            </ul>\n            <div class=\"tab-content\">\n              <div class=\"tab-pane active\" id=\"project-statement-<%= bid_id %>\">\n                <p><%= body %></p>\n              </div>\n              <div class=\"tab-pane\" id=\"overall-statement-<%= bid_id %>\">\n                <p>Lorem ipsum is so fun. <%= body %></p>\n              </div>\n              <div class=\"tab-pane\" id=\"resume-<%= bid_id %>\">\n                <p><%= resume %></p>\n              </div>\n            </div>\n          </div>\n\n        </div>\n        <div class=\"span3 bid-sidebar\" >\n\n          <table>\n            <tr>\n              <td>\n                <div class=\"btn-group\">\n\n                    <a class=\"btn btn-mini unstar-button toggle-starred\"><i class=\"icon-thumbs-up\"></i></a>\n\n                    <a class=\"btn btn-mini btn-primary toggle-no-vote\">&nbsp;&nbsp;&nbsp;&nbsp;</a>\n\n                    <a class=\"btn btn-mini toggle-thumbs-down\"><i class=\"icon-thumbs-down\"></i></a>\n\n                </div>\n\n                &nbsp;\n\n                <div class=\"btn-group\">\n                  <a class=\"btn dropdown-toggle btn-mini\" data-toggle=\"dropdown\" href=\"#\">\n                    More\n                    <span class=\"caret\"></span>\n                  </a>\n                  <ul class=\"dropdown-menu\">\n                    <% if (awarded_at) { %>\n                      <li class=\"active\"><a class=\"toggle-awarded\">hired!</a></li>\n                    <% } else { %>\n                      <li><a class=\"toggle-awarded\">hire me</a></li>\n                    <% } %>\n\n                    <% if (dismissed_at) { %>\n                      <li class=\"active\"><a class=\"toggle-dismissed\">spam</a></a>\n                    <% } else { %>\n                      <li><a class=\"toggle-dismissed\">spam</a></li>\n                    <% } %>\n                  </ul>\n                </div>\n              </td>\n            </tr>\n           </table>\n\n          <div class=\"projects-applied\">\n            <strong>Applied to:</strong>\n            <p>RFP-EZr, Periwinkle Button</p>\n          </div>\n\n          <div class=\"comments-wrapper\"></div>\n\n        </div>\n      </div>\n    </div>\n  </td>\n</tr>"),
  main_bid_template: _.template("<td>\n  <% if (read == 1) { %>\n    <a class=\"btn btn-small btn-circle toggle-read\">&nbsp;</a>\n  <% } else { %>\n    <a class=\"btn btn-small btn-primary btn-circle toggle-read\">&nbsp;</a>\n  <% } %>\n  <% if (anyone_read == 1) { %>\n    <span class=\"anyone-read\">R</span>\n  <% } %>\n</td>\n<td><a class=\"vendor-name toggle-details\"><%= vendor.name %></a></td>\n<td><%= total_stars %></td>\n<td class=\"comment-count\"><%= total_comments %></td>"),
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
