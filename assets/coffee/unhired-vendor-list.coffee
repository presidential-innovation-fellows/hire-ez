$(document).on "click", ".load-more-applicants-button", ->
  $el = $(this)
  $el.button 'loading'
  $table = $el.closest(".project").find(".top-applicant-table")
  project_id = $el.data('project-id')
  page = $el.data('current-page')

  $.getJSON "/vendors/get_more?project_id=#{project_id}&page=#{page}", (data) ->
    $table.find("tbody").append(data.html)

    if data.more
      $el.button 'reset'
      $el.data 'current-page', page + 1
    else
      $el.remove()