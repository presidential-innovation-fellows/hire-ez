
window.Rfpez || (window.Rfpez = {
  Backbone: {}
});

Rfpez.current_page = function(str) {
  if (str === Rfpez.current_page_string) {
    return true;
  } else {
    return false;
  }
};

$(document).on("click", "input[data-checkbox-group]", function(e) {
  var max, numCheckedInputs;
  max = $(this).data('checkbox-max') ? parseInt($(this).data('checkbox-max')) : false;
  numCheckedInputs = $("input[data-checkbox-group=" + ($(this).data('checkbox-group')) + "]").filter(":checked").length;
  if ($(this).is(":checked")) {
    if (max && (numCheckedInputs >= max)) {
      $("input[data-checkbox-group=" + ($(this).data('checkbox-group')) + "]").filter(":not(:checked)").attr('disabled', true);
    }
    return $("input[data-checkbox-group!=" + ($(this).data('checkbox-group')) + "]").removeAttr('checked').attr('disabled', true);
  } else {
    if (max && (numCheckedInputs < max)) {
      $("input[data-checkbox-group=" + ($(this).data('checkbox-group')) + "]").filter(":not(:checked)").removeAttr('disabled');
    }
    if (numCheckedInputs === 0) {
      return $("input[data-checkbox-group!=" + ($(this).data('checkbox-group')) + "]").removeAttr('disabled');
    }
  }
});

$(document).on('shown', '#signinModal', function() {
  return $("#signinModal #email").focus();
});

$(document).on("click", "a[data-confirm]", function(e) {
  if (!confirm(el.data('confirm'))) {
    return e.preventDefault();
  }
});

$(document).on("submit", "#new-contract-form", function(e) {
  if (!$(this).find('input[name=solnbr]').val()) {
    return e.preventDefault();
  }
  return $(this).find("button[type=submit]").button('loading');
});

$(document).on("click", "[data-select-text-on-focus]", function(e) {
  return $(this).select();
});

$(document).on("mouseenter", ".helper-tooltip", function(e) {
  $(this).tooltip();
  return $(this).tooltip('show');
});

$(document).on("mouseleave", ".helper-tooltip", function(e) {
  return $(this).tooltip('hide');
});

$(document).on("ready page:load", function() {
  $("[data-onload-focus]:eq(0)").focus();
  $("span.timeago").timeago();
  $('input, textarea').placeholder();
  if ($("body").hasClass('officer')) {
    $('.datepicker-wrapper').datepicker();
  }
  return Rfpez.current_page_string = $("body").data('current-page');
});
