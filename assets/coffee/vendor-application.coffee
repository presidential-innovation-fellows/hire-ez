$(document).on "change", "input.project-application-check", ->
  $(this).closest('.project').find('.why-great').collapse('toggle')

count_words = ->
  value = $(".why-great-fellow textarea").val()
  count = $.trim(value).split(/\s+/).length
  max = 150
  remaining = if !value then 150 else max - count
  $("#words-remaining").text(remaining)

$(document).on "input", ".why-great-fellow textarea", count_words

$(document).on "ready page:load", count_words