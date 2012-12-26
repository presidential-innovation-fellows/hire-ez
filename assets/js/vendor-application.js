var count_words;

$(document).on("change", "input.project-application-check", function() {
  return $(this).closest('.project').find('.why-great').collapse('toggle');
});

count_words = function() {
  var count, max, remaining, value;
  value = $(".why-great-fellow textarea").val();
  count = $.trim(value).split(/\s+/).length;
  max = 150;
  remaining = !value ? 150 : max - count;
  return $("#words-remaining").text(remaining);
};

$(document).on("input", ".why-great-fellow textarea", count_words);

$(document).on("ready page:load", function() {
  var editor;
  return editor = $('.wysihtml5').wysihtml5({
    image: false
  });
});
