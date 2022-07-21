/**
 * MIT License
 *
 * Copyright (c) 2021-2022 FoxxoSnoot
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

var navbarMoreDropdown = '[data-toggle="topbar-more-dropdown"]';
var navbarUserDropdown = '[data-toggle="topbar-user-dropdown"]';
var navbarUserDropdownMobile = '[data-toggle="topbar-user-dropdown-mobile"]';

$(document).ready(function() {
    $(navbarMoreDropdown).click(function(event) {
        var target = event.target;

        if ($('#topbar-more-dropdown').is(':hidden')) {
            if (targetMatches(true, target, `${navbarMoreDropdown}, ${navbarMoreDropdown} *`)) {
                $(navbarMoreDropdown).addClass('active');
                $('#topbar-more-dropdown').slideDown(300);
            }
        } else if ($('#topbar-more-dropdown').is(':visible')) {
            if (targetMatches(false, target, '#topbar-more-dropdown, #topbar-more-dropdown *')) {
                $(navbarMoreDropdown).removeClass('active');
                $('#topbar-more-dropdown').slideUp(300);
            }
        }
    });

    $(navbarUserDropdown).click(function(event) {
        var target = event.target;

        if ($('#topbar-user-dropdown').is(':hidden')) {
            if (targetMatches(true, target, `${navbarUserDropdown}, ${navbarUserDropdown} *`)) {
                $(navbarUserDropdown).addClass('active');
                $('#topbar-user-dropdown').slideDown(300);
            }
        } else if ($('#topbar-user-dropdown').is(':visible')) {
            if (targetMatches(false, target, '#topbar-user-dropdown, #topbar-user-dropdown *')) {
                $(navbarUserDropdown).removeClass('active');
                $('#topbar-user-dropdown').slideUp(300);
            }
        }
    });

    $(navbarUserDropdownMobile).click(function(event) {
        var target = event.target;

        if ($('#topbar-user-dropdown-mobile').is(':hidden')) {
            if (targetMatches(true, target, `${navbarUserDropdownMobile}, ${navbarUserDropdownMobile} *`)) {
                $(navbarUserDropdownMobile).addClass('active');
                $('#topbar-user-dropdown-mobile').slideDown(300);
            }
        } else if ($('#topbar-user-dropdown-mobile').is(':visible')) {
            if (targetMatches(false, target, '#topbar-user-dropdown-mobile, #topbar-user-dropdown-mobile *')) {
                $(navbarUserDropdownMobile).removeClass('active');
                $('#topbar-user-dropdown-mobile').slideUp(300);
            }
        }
    });
});

function targetMatches(does, eventTarget, target)
{
    if (does) {
        return (eventTarget.matches) ? eventTarget.matches(target) : eventTarget.msMatchesSelector(target);
    } else {
        return (eventTarget.matches) ? !eventTarget.matches(target) : !eventTarget.msMatchesSelector(target);
    }
}
