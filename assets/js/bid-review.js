var keep_bid_in_view, mark_as_spam, mouseover_select_timeout, next_page, no_vote_selection, on_mouseover_select, open_selection, previous_page, thumbs_down_selection, thumbs_up_selection, toggle_unread_selection;

$(document).on("click", "#bid-review-pagination-wrapper li:not(.disabled) a", function(e) {
  var $wrapper, filter, forwardDirection, href, i, no_longer_visible_count, params, total, val;
  e.preventDefault();
  $wrapper = $("#bid-review-pagination-wrapper");
  forwardDirection = $(this).hasClass('previous') ? false : true;
  total = $wrapper.data('total');
  href = $wrapper.data('href');
  filter = $wrapper.data('filter') || false;
  params = {
    skip: parseInt($wrapper.data('skip') || 0),
    sort: $wrapper.data('sort'),
    query: $wrapper.data('query')
  };
  if (forwardDirection) {
    no_longer_visible_count = 0;
    Rfpez.Backbone.Bids.each(function(b) {
      if (filter === "unread") {
        if (b.attributes.read === "1" || b.attributes.dismissed_at) {
          return no_longer_visible_count++;
        }
      } else if (filter === "starred") {
        if (!b.attributes.starred || b.attributes.starred === "0" || b.attributes.dismissed_at) {
          return no_longer_visible_count++;
        }
      } else if (filter === "thumbs-downed") {
        if (!b.attributes.thumbs_downed || b.attributes.thumbs_downed === "0" || b.attributes.dismissed_at) {
          return no_longer_visible_count++;
        }
      } else if (filter === "hired") {
        if (!b.attributes.awarded_at || b.attributes.awarded_at === "0" || b.attributes.dismissed_at) {
          return no_longer_visible_count++;
        }
      } else if (filter === "spam") {
        if (!b.attributes.dismissed_at || b.attributes.dismissed_at === "0") {
          return no_longer_visible_count++;
        }
      } else {
        if (b.attributes.dismissed_at) {
          return no_longer_visible_count++;
        }
      }
    });
    params.skip = params.skip - no_longer_visible_count + 10;
  } else {
    params.skip = params.skip - 10;
  }
  if (params.skip < 1) {
    params.skip = false;
  }
  for (i in params) {
    val = params[i];
    if (!val) {
      delete params[i];
    }
  }
  return Turbolinks.visit("" + href + "?" + ($.param(params)));
});

$(document).on("click", ".show-refer", function(e) {
  e.preventDefault();
  return $(this).addClass('hide').next('form').removeClass('hide');
});

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

next_page = function() {
  return $("#bid-review-pagination-wrapper .next").click();
};

previous_page = function() {
  return $("#bid-review-pagination-wrapper .previous").click();
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

key('right', next_page);

key('left', previous_page);

key('/, shift+/, ⌘+/, ctrl+/', Rfpez.toggle_keyboard_shortcuts);

key('up, down, return, o', function() {
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
