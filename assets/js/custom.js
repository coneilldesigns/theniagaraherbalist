"use strict";

jQuery('body').on('mouseenter mouseleave', '.dropdown', function (e) {
  var dropdown = jQuery(e.target).closest('.dropdown');
  var menu = jQuery('.dropdown-menu', dropdown);
  dropdown.addClass('show');
  menu.addClass('show');
  setTimeout(function () {
    dropdown[dropdown.is(':hover') ? 'addClass' : 'removeClass']('show');
    menu[dropdown.is(':hover') ? 'addClass' : 'removeClass']('show');
  }, 300);
});