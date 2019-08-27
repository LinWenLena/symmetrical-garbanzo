/**
 * Created by mircobaseniak on 13.03.18.
 */

// activate strict mode syntax
"use strict";

/* -------------
 Actions
 ------------- */

$(document).ready(function() {

    // toggle sidebar visibility by click on button
    toggleClassOnClick('.toggle-sidebar-visibility', ['.toggle-sidebar-visibility', '.sidebar'], 'visible');

    // animate (show and hide) the navigation sub menus
    animateNavSubMenu();
});

$(window).scroll(function() {
    var st = $(this).scrollTop();

    // toggle header fixed position based on scroll position
    toggleClassOnScroll(st, 140, ['header', 'section.main'], 'scrolled');
});

/* -------------
 Functions
 ------------- */

/**
 * Toggle a certail class of a specific element by click on an element
 * @param clicked - div was clicked on
 * @param element - div/s at which the class is to be toggle
 * @param classname - class that should be toggle
 */
function toggleClassOnClick(clicked, element, classname) {
    if ($(clicked).length && $(element).length) {
        $(clicked).click(function() {
            $(element).toggleClass(classname);
        });
    } else {
        throw new NullPointerException();
    }
}

/**
 * Toggles a certail class of a specific element based on the scroll position
 * @param st - scroll top
 * @param sp - scroll position at which the class is to be toggle
 * @param element - div/s at which the class is to be toggle
 * @param classname - class that should be toggle
 */
function toggleClassOnScroll(st, sp, element, classname) {
    if ($(element).length) {
        $(element).each(function () {
           $(this).toggleClass(classname, st > sp);
       });
    } else {
        throw new NullPointerException();
    }
}

/**
 * Animate (show and hide) the navigation sub menus
 */
function animateNavSubMenu() {
    $('li.menu-item-has-children').click(function() {
        var subMenu = $(this).find('ul.sub-menu'); // current sub menu
        var liCount = subMenu.children().length; // number of lis of the sub menu
        var liHeight = subMenu.children().height(); // height if a li
        // fade out all visible sub menus
        $('li.menu-item-has-children ul.sub-menu').animate({height: 0}, {duration: 200, queue: false});
        if (subMenu.height() === 0) { // if sub menu was hidden
            // fade in the current sub menu
            subMenu.animate({
                'height': liHeight*liCount+12,
                'padding-top': 6,
                'padding-bottom': 6
            }, {duration: 200, queue: false});
        } else {
            // fade out the current sub menu
            subMenu.animate({
                'height': 0,
                'padding-top': 0,
                'padding-bottom': 0
            }, {duration: 200, queue: false});
        }
        // toggle class to highlight visible sub menu
        $(this).toggleClass('visible');
    });
}