
Rfpez.Backbone.BidView = Backbone.View.extend({
  tagName: "tbody",
  className: "bid",
  template: _.template("<tr class=\"main-bid\">\n  <td>as</td>\n</tr>\n<tr>\n  <td class=\"bid-details-wrapper\" colspan=\"5\">\n    <div class=\"collapse\">\n      <div class=\"bid-details row-fluid\">\n        <div class=\"span8\">\n          <div class=\"bid-tab-wrapper\">\n            <ul class=\"nav nav-tabs\">\n              <li class=\"active\"><a href=\"#body<%= id %>\" data-toggle=\"tab\">Body</a></li>\n              <li><a href=\"#general_paragraph<%= id %>\" data-toggle=\"tab\">General Paragraph</a></li>\n              <li><a href=\"#resume<%= id %>\" data-toggle=\"tab\">Resume</a></li>\n              <li><a href=\"#links<%= id %>\" data-toggle=\"tab\">Links & Contact Info</a></li>\n            </ul>\n\n            <div class=\"tab-content\">\n              <div class=\"tab-pane active\" id=\"body<%= id %>\"><%= body %></div>\n              <div class=\"tab-pane\" id=\"general_paragraph<%= id %>\"><%= vendor.general_paragraph %></div>\n              <div class=\"tab-pane\" id=\"resume<%= id %>\"><%= vendor.resume %></div>\n              <div class=\"tab-pane\" id=\"links<%= id %>\">\n                <dl>\n                  <% if (vendor.link_1){ %>\n                    <dt>Link #1</dt>\n                    <dd><a href=\"<%= vendor.link_1 %>\" target=\"_blank\"><%= vendor.link_1 %></a></dd>\n                  <% } %>\n                  <% if (vendor.link_2){ %>\n                    <dt>Link #2</dt>\n                    <dd><a href=\"<%= vendor.link_2 %>\" target=\"_blank\"><%= vendor.link_2 %></a></dd>\n                  <% } %>\n                  <% if (vendor.link_3){ %>\n                    <dt>Link #3</dt>\n                    <dd><a href=\"<%= vendor.link_3 %>\" target=\"_blank\"><%= vendor.link_3 %></a></dd>\n                  <% } %>\n\n                  <dt>Location</dt>\n                  <dd><a href=\"https://maps.google.com/?q=<%= vendor.location %>\" target=\"_blank\"><%= vendor.location %></a></dd>\n\n                  <dt>Email</dt>\n                  <dd><a href=\"mailto:<%= vendor.email %>\"><%= vendor.email %></a></dd>\n\n                  <dt>Phone</dt>\n                  <dd><%= vendor.phone %></dd>\n                </dl>\n              </div>\n            </div>\n          </div>\n        </div>\n        <div class=\"span4\">\n          <div class=\"transfer-bid-wrapper\"></div>\n          <div class=\"comments-wrapper\"></div>\n        </div>\n      </div>\n    </div>\n  </td>\n</tr>"),
  transfer_bid_template: _.template("<strong>Applied to:</strong>\n<p><%= vendor.titles_of_projects_applied_for.join(\", \") %></p>\n\n<form action=\"/projects/<%= project_id %>/bids/<%= id %>/transfer\" method=\"POST\">\n  <strong>Transfer to:</strong>\n  <select class=\"select-inline\" name=\"project_id\">\n    <% _.each(vendor.projects_not_applied_for, function(project){ %>\n      <option value=\"<%= project.id %>\"><%= project.title %></option>\n    <% }); %>\n  </select>\n  <button class=\"btn btn-small\">Send!</button>\n</form>"),
  main_bid_template: _.template("<td>\n  <% if (read == 1) { %>\n    <a class=\"btn btn-small btn-circle toggle-read\">&nbsp;</a>\n  <% } else { %>\n    <a class=\"btn btn-small btn-primary btn-circle toggle-read\">&nbsp;</a>\n  <% } %>\n  <% if (anyone_read == 1) { %>\n    <span class=\"anyone-read\">R</span>\n  <% } %>\n</td>\n<td>\n  <a class=\"vendor-name toggle-details\">\n    <%= vendor.name %>\n    &nbsp;\n    <% if (awarded_at) { %>\n      <span class=\"label label-success\">Hired</span>\n    <% } %>\n    <% if (dismissed_at) { %>\n      <span class=\"label label-important\">Spam</span>\n    <% } %>\n  </a>\n</td>\n<td><%= total_score %></td>\n<td class=\"comment-count\"><%= vendor.total_comments %></td>\n<td>\n  <div class=\"btn-group\">\n\n    <% if (starred == 1) { %>\n      <a class=\"btn btn-mini btn-primary unstar-button toggle-starred\"><i class=\"icon-thumbs-up\"></i></a>\n    <% } else { %>\n      <a class=\"btn btn-mini unstar-button toggle-starred\"><i class=\"icon-thumbs-up\"></i></a>\n    <% } %>\n\n    <% if (starred != 1 && thumbs_downed != 1) { %>\n      <a class=\"btn btn-mini btn-primary toggle-no-vote\"><i class=\"icon-thumbs-sideways\"></i></a>\n    <% } else { %>\n      <a class=\"btn btn-mini toggle-no-vote\"><i class=\"icon-thumbs-sideways\"></i></a>\n    <% } %>\n\n    <% if (thumbs_downed == 1) { %>\n      <a class=\"btn btn-mini btn-primary toggle-thumbs-down\"><i class=\"icon-thumbs-down\"></i></a>\n    <% } else { %>\n      <a class=\"btn btn-mini toggle-thumbs-down\"><i class=\"icon-thumbs-down\"></i></a>\n    <% } %>\n\n\n  </div>\n\n  &nbsp;\n\n  <div class=\"btn-group\">\n    <a class=\"btn dropdown-toggle btn-mini\" data-toggle=\"dropdown\" href=\"#\">\n      More\n      <span class=\"caret\"></span>\n    </a>\n    <ul class=\"dropdown-menu\">\n      <% if (awarded_at) { %>\n        <li class=\"active\"><a class=\"toggle-awarded\">hired!</a></li>\n      <% } else { %>\n        <li><a class=\"toggle-awarded\">hire me</a></li>\n      <% } %>\n\n      <% if (dismissed_at) { %>\n        <li class=\"active\"><a class=\"toggle-dismissed\">spam</a></a>\n      <% } else { %>\n        <li><a class=\"toggle-dismissed\">spam</a></li>\n      <% } %>\n    </ul>\n  </div>\n\n</td>"),
  events: {
    "click .toggle-read": "toggleRead",
    "click .toggle-starred": "toggleStarred",
    "click .toggle-dismissed": "toggleDismissed",
    "click .toggle-awarded": "toggleAwarded",
    "click .toggle-details": "toggleDetails",
    "click .toggle-thumbs-down": "toggleThumbsDown",
    "click .toggle-no-vote": "toggleNoVote"
  },
  initialize: function() {
    this.model.bind("create", this.render, this);
    this.model.bind("change", this.renderMainBid, this);
    this.model.bind("destroy", this.remove, this);
    return this.expanded = this.options.expanded;
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
    if (this.expanded) {
      this.renderComments();
      this.renderTransferBid();
      this.$el.find(".collapse").addClass('in');
    }
    return this;
  },
  renderMainBid: function() {
    return this.$el.find(".main-bid").html(this.main_bid_template(this.model.toJSON()));
  },
  renderComments: function() {
    this.comments = new Rfpez.Backbone.BidCommentsView({
      parent_view: this,
      vendor_id: this.model.attributes.vendor_id
    });
    return this.$el.find(".comments-wrapper").html(this.comments.el);
  },
  renderTransferBid: function() {
    return this.$el.find(".transfer-bid-wrapper").html(this.transfer_bid_template(this.model.toJSON()));
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
  toggleStarred: function(save) {
    var attributes;
    if (save == null) {
      save = true;
    }
    attributes = {};
    if (this.model.attributes.starred === "1") {
      attributes["starred"] = false;
      this.model.attributes.total_stars--;
    } else {
      attributes["starred"] = true;
      this.model.attributes.total_stars++;
      if (this.model.attributes.thumbs_downed === "1") {
        this.toggleThumbsDown(false);
      }
    }
    this.calculateTotalScore();
    if (save) {
      return this.model.save(attributes);
    } else {
      return this.model.set(attributes);
    }
  },
  toggleThumbsDown: function(save) {
    var attributes;
    if (save == null) {
      save = true;
    }
    attributes = {};
    if (this.model.attributes.thumbs_downed === "1") {
      attributes["thumbs_downed"] = false;
      this.model.attributes.total_thumbs_down--;
    } else {
      attributes["thumbs_downed"] = true;
      this.model.attributes.total_thumbs_down++;
      if (this.model.attributes.starred === "1") {
        this.toggleStarred(false);
      }
    }
    this.calculateTotalScore();
    if (save) {
      return this.model.save(attributes);
    } else {
      return this.model.set(attributes);
    }
  },
  toggleAwarded: function() {
    return this.model.save({
      awarded_at: this.model.attributes.awarded_at ? false : true
    });
  },
  toggleNoVote: function() {
    this.$el.find(".toggle-starred.btn-primary").click();
    return this.$el.find(".toggle-thumbs-down.btn-primary").click();
  },
  toggleDetails: function() {
    var _this = this;
    if (this.model.attributes.read !== "1" && !this.$el.find(".bid-details-wrapper .collapse").hasClass('in')) {
      this.toggleRead();
    }
    this.$el.find(".bid-details-wrapper .collapse").collapse('toggle');
    if (!this.comments) {
      this.renderComments();
    }
    if (this.$el.find(".transfer-bid-wrapper div").length === 0) {
      return this.model.fetchDetails(function() {
        return _this.renderTransferBid();
      });
    }
  },
  calculateTotalScore: function() {
    return this.model.attributes.total_score = this.model.attributes.total_stars - this.model.attributes.total_thumbs_down;
  },
  incrementCommentCount: function() {
    this.model.attributes.vendor.total_comments++;
    return this.renderMainBid();
  },
  decrementCommentCount: function() {
    this.model.attributes.vendor.total_comments--;
    return this.renderMainBid();
  }
});
