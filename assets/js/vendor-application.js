
$(document).on("change", "input.project-application-check", function() {
  return $(this).closest('.project').find('.why-great').collapse('toggle');
});
