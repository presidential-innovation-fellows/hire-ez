on_mouseover_select = true
mouseover_select_timeout = false

# Hack to keep us from "selecting" a new bid when the window is automatically scrolled.
# Suggestions for better solutions welcomed.
keep_bid_in_view = (bid, scrollTo) ->
  on_mouseover_select = false
  clearTimeout(mouseover_select_timeout)

  if scrollTo is "bid"
    bottom = bid.offset().top + bid.height()
    current_bottom = $(window).scrollTop() + $(window).height()

    top = bid.offset().top
    current_top = $(window).scrollTop()

    if (current_bottom < bottom) then $('html, body').scrollTop(bottom - $(window).height())
    if (current_top > top) then $('html, body').scrollTop(bid.offset().top)

  else if scrollTo is "top"
    $('html, body').scrollTop(0)

  mouseover_select_timeout = setTimeout ->
    on_mouseover_select = true
  , 200

Rfpez.toggle_keyboard_shortcuts = ->
  $("#keyboard-shortcuts-modal").modal('toggle')

Rfpez.select_bid = (bid, scrollTo) ->
  $(".bid").removeClass('selected')
  bid.addClass('selected')
  if scrollTo then keep_bid_in_view(bid, scrollTo)

Rfpez.move_bid_selection = (direction) ->
  selected_bid = $(".bid.selected:eq(0)")
  return if !selected_bid
  all_bids = $("tbody.bid")
  selected_index = all_bids.index(selected_bid)

  if direction is "up"
    if selected_index is 0
      return Rfpez.select_bid(selected_bid, "top")

    new_index = selected_index - 1
  else # direction is "down"
    new_index = selected_index + 1

  new_selection = $("tbody.bid").eq(new_index)
  if new_selection.length > 0 then Rfpez.select_bid(new_selection, "bid")

thumbs_up_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".toggle-starred").click()

thumbs_down_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".toggle-thumbs-down").click()

no_vote_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".toggle-no-vote").click()

open_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".vendor-name").click()

mark_as_spam = (e) ->
  e.preventDefault()
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".toggle-dismissed").click()

toggle_unread_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".toggle-read").click()

key 'k', ->
  Rfpez.move_bid_selection("up")

key 'j', ->
  Rfpez.move_bid_selection("down")

key 's', thumbs_up_selection
key 'f', thumbs_down_selection
key 'd', no_vote_selection
key 'return, o', open_selection
key '⌘+backspace, ctrl+backspace, ⌘+delete, ctrl+delete', mark_as_spam
key 'a', toggle_unread_selection


key '/, shift+/, ⌘+/, ctrl+/', Rfpez.toggle_keyboard_shortcuts

$(document).on "ready page:load", ->
  if Rfpez.current_page("bid-review")
    Rfpez.move_bid_selection("down")
$(document).on "mouseover.selectbidmouseover", ".bid", ->
  if Rfpez.current_page("bid-review") and on_mouseover_select
    Rfpez.select_bid($(this), false)
