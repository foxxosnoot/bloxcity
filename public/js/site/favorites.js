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

var userId;

$(function() {
    getFavorites(1);
});

function getFavorites(page)
{
    userId = $('meta[name="user-info"]').attr('data-id');

    $.get('/api/v1/user/favorites', {
        id: userId,
        page: page
    }).done(function(data) {
        $('#favorites').html('');

        if (typeof data.success !== 'undefined' && !data.success) {
            $('#favorites').removeClass('grid-x grid-margin-x').css('min-height', 'auto').html(`<div class="profile-no-results">${data.message}</div>`);
        } else {
            $('#favorites').addClass('grid-x grid-margin-x').css('min-height', '215px');

            $.each(data.items, function() {
                $('#favorites').append(`
                <div class="cell small-6 medium-3">
                    <a href="/market/${this.id}" title="${this.name}">
                        <img class="profile-inventory-item-thumbnail" src="${this.thumbnail_url}">
                    </a>
                    <a href="/market/${this.id}" class="profile-inventory-item-name" title="${this.name}">${this.name}</a>
                    <div class="profile-inventory-item-creator">Creator: <a href="/profile/${this.creator.username}">${this.creator.username}</a></div>
                    <div class="push-25"></div>
                </div>`);
            });

            if (data.total_pages == 1) {
                $('#favoritesButtons').html('');
            } else {
                var previousDisabled = (data.current_page == 1) ? 'disabled' : '';
                var nextDisabled = (data.current_page == data.total_pages) ? 'disabled' : '';
                var previousPage = data.current_page - 1;
                var nextPage = data.current_page + 1;

                $('#favoritesButtons').html(`
                <div class="cell small-12 text-center">
                    <button onclick="getFavorites(${previousPage})" class="button button-red" ${previousDisabled}>Previous</button>
                    <span style="margin-left:5px;margin-right:5px;">${data.current_page} of ${data.total_pages}</span>
                    <button onclick="getFavorites(${nextPage})" class="button button-green" ${nextDisabled}>Next</button>
                </div>`);
            }
        }
    }).fail(function() {
        $('#favorites').html('').removeClass('grid-x grid-margin-x').css('min-height', 'auto').html('<div class="profile-no-results">Unable to retrieve favorites. Please refresh.</div>');
    });
}
