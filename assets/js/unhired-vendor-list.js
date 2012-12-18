
$(document).on("click", ".load-more-applicants-button", function() {
  var $el, $table, page, project_id;
  $el = $(this);
  $el.button('loading');
  $table = $el.closest(".project").find(".top-applicant-table");
  project_id = $el.data('project-id');
  page = $el.data('current-page');
  return $.getJSON("/vendors/get_more?project_id=" + project_id + "&page=" + page, function(data) {
    $table.find("tbody").append(data.html);
    if (data.more) {
      $el.button('reset');
      return $el.data('current-page', page + 1);
    } else {
      return $el.remove();
    }
  });
});
