window.Rfpez ||= {
  Backbone: {}
}

Rfpez.current_page = (str) ->
  if str is Rfpez.current_page_string
    true
  else
    false

$(document).on "click", "input[data-checkbox-group]", (e) ->
  max = if $(this).data('checkbox-max') then parseInt($(this).data('checkbox-max')) else false
  numCheckedInputs = $("input[data-checkbox-group=#{$(@).data('checkbox-group')}]").filter(":checked").length

  if $(this).is(":checked")
    # if we're checking, and this is the second checkbox we've checked,
    # disable the other checkboxes in this group
    if max and (numCheckedInputs >= max)
      $("input[data-checkbox-group=#{$(@).data('checkbox-group')}]").filter(":not(:checked)").attr('disabled', true)

    $("input[data-checkbox-group!=#{$(@).data('checkbox-group')}]").removeAttr('checked').attr('disabled', true)

  else
    if max and (numCheckedInputs < max)
      $("input[data-checkbox-group=#{$(@).data('checkbox-group')}]").filter(":not(:checked)").removeAttr('disabled')

    if numCheckedInputs is 0
      $("input[data-checkbox-group!=#{$(@).data('checkbox-group')}]").removeAttr('disabled')

$(document).on 'shown', '#signinModal', ->
  $("#signinModal #email").focus()

$(document).on "click", "a[data-confirm]", (e) ->
  e.preventDefault();
  el = $(this)
  if confirm(el.data('confirm'))
    window.location = el.attr('href')

$(document).on "submit", "#new-contract-form", (e) ->
  return e.preventDefault() unless $(this).find('input[name=solnbr]').val()
  $(this).find("button[type=submit]").button('loading')

$(document).on "click", "[data-select-text-on-focus]", (e) ->
  $(this).select()

$(document).on "mouseenter", ".helper-tooltip", (e) ->
  $(this).tooltip()
  $(this).tooltip('show')

$(document).on "mouseleave", ".helper-tooltip", (e) ->
  $(this).tooltip('hide')

$(document).on "ready page:load", ->
  $("[data-onload-focus]:eq(0)").focus()
  $("span.timeago").timeago()
  $('input, textarea').placeholder()

  if $("body").hasClass('officer')
    $('.datepicker-wrapper').datepicker()

  Rfpez.current_page_string = $("body").data('current-page')
