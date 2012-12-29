$(document).on "change", "input.project-application-check", ->
  $(this).closest('.project').find('.why-great').collapse('toggle')

count_words = ->
  value = $(".why-great-fellow textarea").val()
  count = $.trim(value).split(/\s+/).length
  max = 150
  remaining = if !value then 150 else max - count
  $("#words-remaining").text(remaining)

$(document).on "input", ".why-great-fellow textarea", count_words

# Prevent submitting the form when a user hits enter on the location autocomplete.
$(document).on "keydown", "#locationInput", (e) ->
  e.preventDefault() if e.keyCode is 13

$(document).on "ready page:load", ->
  editor = $('.wysihtml5').wysihtml5({image: false})

  if Rfpez.current_page("new-vendor")
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('locationInput'), {})

    google.maps.event.addListener autocomplete, 'place_changed', ->
      place = autocomplete.getPlace()
      if place.geometry
        $("#latitudeInput").val(place.geometry.location.lat())
        $("#longitudeInput").val(place.geometry.location.lng())
      else
        $("#latitudeInput").val('')
        $("#longitudeInput").val('')
