var keep_bid_in_view, mark_as_spam, mouseover_select_timeout, no_vote_selection, on_mouseover_select, open_selection, thumbs_down_selection, thumbs_up_selection, toggle_unread_selection;

on_mouseover_select = true;

mouseover_select_timeout = false;

keep_bid_in_view = function(bid, scrollTo) {
  var bottom, current_bottom, current_top, top;
  on_mouseover_select = false;
  clearTimeout(mouseover_select_timeout);
  if (scrollTo === "bid") {
    bottom = bid.offset().top + bid.height();
    current_bottom = $(window).scrollTop() + $(window).height();
    top = bid.offset().top;
    current_top = $(window).scrollTop();
    if (current_bottom < bottom) {
      $('html, body').scrollTop(bottom - $(window).height());
    }
    if (current_top > top) {
      $('html, body').scrollTop(bid.offset().top);
    }
  } else if (scrollTo === "top") {
    $('html, body').scrollTop(0);
  }
  return mouseover_select_timeout = setTimeout(function() {
    return on_mouseover_select = true;
  }, 200);
};

Rfpez.toggle_keyboard_shortcuts = function() {
  return $("#keyboard-shortcuts-modal").modal('toggle');
};

Rfpez.select_bid = function(bid, scrollTo) {
  $(".bid").removeClass('selected');
  bid.addClass('selected');
  if (scrollTo) {
    return keep_bid_in_view(bid, scrollTo);
  }
};

Rfpez.move_bid_selection = function(direction) {
  var all_bids, new_index, new_selection, selected_bid, selected_index;
  selected_bid = $(".bid.selected:eq(0)");
  if (!selected_bid) {
    return;
  }
  all_bids = $("tbody.bid");
  selected_index = all_bids.index(selected_bid);
  if (direction === "up") {
    if (selected_index === 0) {
      return Rfpez.select_bid(selected_bid, "top");
    }
    new_index = selected_index - 1;
  } else {
    new_index = selected_index + 1;
  }
  new_selection = $("tbody.bid").eq(new_index);
  if (new_selection.length > 0) {
    return Rfpez.select_bid(new_selection, "bid");
  }
};

thumbs_up_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".toggle-starred").click();
};

thumbs_down_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".toggle-thumbs-down").click();
};

no_vote_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".toggle-no-vote").click();
};

open_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".vendor-name").click();
};

mark_as_spam = function(e) {
  var selected_bid;
  e.preventDefault();
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".toggle-dismissed").click();
};

toggle_unread_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".toggle-read").click();
};

key('k', function() {
  return Rfpez.move_bid_selection("up");
});

key('j', function() {
  return Rfpez.move_bid_selection("down");
});

key('s', thumbs_up_selection);

key('f', thumbs_down_selection);

key('d', no_vote_selection);

key('return, o', open_selection);

key('⌘+backspace, ctrl+backspace, ⌘+delete, ctrl+delete', mark_as_spam);

key('a', toggle_unread_selection);

key('/, shift+/, ⌘+/, ctrl+/', Rfpez.toggle_keyboard_shortcuts);

key('up, down', function() {
  on_mouseover_select = false;
  clearTimeout(mouseover_select_timeout);
  return mouseover_select_timeout = setTimeout(function() {
    return on_mouseover_select = true;
  }, 200);
});

$(document).on("ready page:load", function() {
  if (Rfpez.current_page("bid-review")) {
    return Rfpez.move_bid_selection("down");
  }
});

$(document).on("mouseover.selectbidmouseover", ".bid", function() {
  if (Rfpez.current_page("bid-review") && on_mouseover_select) {
    return Rfpez.select_bid($(this), false);
  }
});
