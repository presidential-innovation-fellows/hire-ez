
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

$(document).on("click", "[data-checkbox-max] input[type=checkbox]", function(e) {
  var group, inputs, max, numCheckedInputs;
  group = $(this).closest('[data-checkbox-group]').data('checkbox-group');
  max = parseInt($(this).closest('[data-checkbox-max]').data('checkbox-max'));
  inputs = group ? $("[data-checkbox-group=" + group + "] input[type=checkbox]") : $("[data-checkbox-max] input[type=checkbox]");
  numCheckedInputs = inputs.filter(":checked").length;
  if ($(this).is(":checked") && numCheckedInputs === max) {
    return inputs.filter(":not(:checked)").attr('disabled', true);
  } else if (numCheckedInputs < max) {
    return inputs.filter(":not(:checked)").removeAttr('disabled');
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
