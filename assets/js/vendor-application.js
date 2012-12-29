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

$(document).on("keydown", "#locationInput", function(e) {
  if (e.keyCode === 13) {
    return e.preventDefault();
  }
});

$(document).on("ready page:load", function() {
  var autocomplete, editor;
  editor = $('.wysihtml5').wysihtml5({
    image: false
  });
  if (Rfpez.current_page("new-vendor")) {
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('locationInput'), {});
    return google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place;
      place = autocomplete.getPlace();
      if (place.geometry) {
        $("#latitudeInput").val(place.geometry.location.lat());
        return $("#longitudeInput").val(place.geometry.location.lng());
      } else {
        $("#latitudeInput").val('');
        return $("#longitudeInput").val('');
      }
    });
  }
});
