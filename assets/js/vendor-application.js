var count_words;

$(document).on("change", "input.project-application-check", function() {
  return $(this).closest('.project').find('.why-great').collapse('toggle');
});

count_words = function() {
  var count, max, value;
  value = $(".why-great-fellow textarea").val();
  count = $.trim(value).split(/\s+/).length;
  max = 150;
  return $("#words-remaining").text(max - count);
};

$(document).on("input", ".why-great-fellow textarea", count_words);

$(document).on("ready page:load", count_words);
