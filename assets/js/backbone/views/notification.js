
Rfpez.Backbone.NotificationView = Backbone.View.extend({
  tagName: "div",
  className: "notification",
  template: _.template("<i class=\"<%= js_parsed.icon %>\"></i>\n<%= js_parsed.text %>\n<div class=\"date\"><span class=\"timeago\" title=\"<%= formatted_created_at %>\"></span></div>"),
  parse: function() {
    var icon, text;
    if (this.model.attributes.notification_type === "Dismissal") {
      text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.name + "'s</a> bid was dismissed. ";
      icon = "icon-thumbs-down";
    } else if (this.model.attributes.notification_type === "Undismissal") {
      text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.name + "'s</a> bid was un-dismissed. ";
      icon = "icon-repeat";
    } else if (this.model.attributes.notification_type === "BidSubmit") {
      text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.name + "</a> submitted a bid. ";
      icon = "icon-list-alt";
    } else if (this.model.attributes.notification_type === "Award") {
      text = " The Contract was awarded to <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.name + "</a>. ";
      icon = "icon-thumbs-up";
    } else if (this.model.attributes.notification_type === "ProjectCollaboratorAdded") {
      text = " " + this.model.attributes.payload.officer.user.email + " was added as a collaborator. ";
      icon = "icon-user";
    }
    return {
      text: text != null ? text : this.model.attributes.notification_type,
      icon: icon != null ? icon : "icon-arrow-right"
    };
  },
  initialize: function() {
    this.model.bind("change", this.render, this);
    return this.model.bind("destroy", this.remove, this);
  },
  render: function() {
    this.$el.html(this.template(_.extend(this.model.toJSON(), {
      js_parsed: this.parse()
    })));
    return this;
  },
  clear: function() {
    return this.model.clear();
  }
});
