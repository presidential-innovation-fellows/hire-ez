window.Rfpez ||= {
  Backbone: {}
}

Rfpez.current_page = (str) ->
  if str is Rfpez.current_page_string
    true
  else
    false

$(document).on "click", "[data-checkbox-max] input[type=checkbox]", (e) ->
  group = $(@).closest('[data-checkbox-group]').data('checkbox-group')
  max = parseInt $(@).closest('[data-checkbox-max]').data('checkbox-max')

  inputs = if group then $("[data-checkbox-group=#{group}] input[type=checkbox]") else $("[data-checkbox-max] input[type=checkbox]")
  numCheckedInputs = inputs.filter(":checked").length

  if $(@).is(":checked") and numCheckedInputs is max
    inputs.filter(":not(:checked)").attr('disabled', true)

  else if numCheckedInputs < max
    inputs.filter(":not(:checked)").removeAttr('disabled')

$(document).on 'shown', '#signinModal', ->
  $("#signinModal #email").focus()

$(document).on "click", "a[data-confirm]", (e) ->
  e.preventDefault() unless confirm(el.data('confirm'))

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
