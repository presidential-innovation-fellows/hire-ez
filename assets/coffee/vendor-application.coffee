$(document).on "change", "input.project-application-check", ->
  $(this).closest('.project').find('.why-great').collapse('toggle')
