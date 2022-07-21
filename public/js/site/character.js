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

var characterInfo;
var currentCategory;
var originalAngle = $('meta[name="user-data"]').attr('data-angle');
var currentAngle = $('meta[name="user-data"]').attr('data-angle');
var originalAvatar;
var originalHeadshot;
var bodyColor;
var currentPart = 'head';

$(function() {
    getInventory('hats', 1);
    getWearing();

    characterInfo = $('meta[name="character-info"]');
    currentCategory = 'hats';

    $('.avatar-item-category').click(function() {
        $(`.avatar-item-category[data-category='${currentCategory}']`).removeClass('active');
        $(this).addClass('active');

        currentCategory = $(this).attr('data-category');

        getInventory(currentCategory, 1);
    });

    $('.avatar-body-part').click(function() {
        currentPart = $(this).attr('data-part');

        // switch (currentPart) {
        //     case 'head':
        //         newHeader = 'Head';
        //         break;
        //     case 'torso':
        //         newHeader = 'Torso';
        //         break;
        //     case 'left_arm':
        //         newHeader = 'Left Arm';
        //         break;
        //     case 'right_arm':
        //         newHeader = 'Right Arm';
        //         break;
        //     case 'left_leg':
        //         newHeader = 'Left Leg';
        //         break;
        //     case 'right_leg':
        //         newHeader = 'Right Leg';
        //         break;
        //     default:
        //         newHeader = '???';
        // }

        // $('#currentPart').text(newHeader);
        $('#colors').show(function() {
            document.body.addEventListener('click', closePalette, false);
        });
    });

    $('.avatar-body-color').click(function() {
        if ($(this).attr('disabled') != 'disabled') {
            bodyColor = $(this).css('background-color');

            update('color', $(this).attr('data-color'));
        }
    });

    $('[data-angle]').click(function() {
        if ($(this).attr('disabled') != 'disabled') {
            currentAngle = $(this).attr('data-angle');

            update('angle', currentAngle);
        }
    });
});

function closePalette(e) {
    if (e.target.id != 'colors' && e.target.id != 'colorsText') {
        document.body.removeEventListener('click', closePalette, false);
        $('#colors').hide();
    }
}

function src()
{
    $.get('/character/src').done(function(data) {
        $('#avatar').attr('src', data.avatar);
        $('#topbarAvatar').attr('src', data.headshot);
        $('#topbarAvatarMobile').attr('src', data.headshot);
    }).fail(function() {
        $('#avatar').attr('src', originalAvatar);
        $('#topbarAvatar').attr('src', originalHeadshot);
        $('#topbarAvatarMobile').attr('src', originalHeadshot);
    });
}

function update(action, id)
{
    var params = {};
    originalAvatar = $('#avatar').attr('src');
    originalHeadshot = $('#topbarAvatar').attr('src');

    $('#avatar').attr('src', characterInfo.attr('data-spinner'));
    $('#avatarError').html('');
    $('.avatar-body-color').addClass('inProgress').attr('disabled', true);
    $('.button').attr('disabled', true);
    $('.avatar-body-part').attr('disabled', true);

    if (action == 'wear') {
        params = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            action: action,
            item_id: id
        };
    } else if (action == 'unwear') {
        params = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            action: action,
            type: id
        };
    } else if (action == 'color') {
        params = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            action: action,
            color: id,
            part: currentPart
        };
    } else if (action == 'angle') {
        params = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            action: action,
            color: id,
            angle: currentAngle
        };
    }

    $.post('/character/update', params).done(function(data) {
        if (typeof data.success !== 'undefined' && !data.success) {
            $('#avatar').attr('src', originalAvatar);
            $('#avatarError').text(data.message);
        } else {
            src();
            getWearing();

            if (action == 'color') {
                $(`.avatar-body-part[data-part='${currentPart}']`).css('background-color', bodyColor);
            }
        }
    }).fail(function() {
        $('#avatar').attr('src', originalAvatar);
        $('#avatarError').text('An unexpected error has occurred.');
    }).always(function() {
        $('.avatar-body-color').removeClass('inProgress').attr('disabled', false);
        $('.button').attr('disabled', false);
        $('.avatar-body-part').attr('disabled', false);
        $(`button[data-angle='${currentAngle}']`).attr('disabled', true);
    });
}

function getWearing()
{
    $.get('/character/wearing').done(function(data) {
        $('#currentlyWearing').html('');

        if (typeof data.success !== 'undefined' && !data.success) {
            $('#currentlyWearing').removeClass('grid-x grid-margin-x').html(`
            <div class="text-center">
                <h1><i class="icon icon-sad"></i></h1>
                <h5>${data.message}</h5>
            </div>`);
        } else {
            $('#currentlyWearing').addClass('grid-x grid-margin-x');

            $.each(data, function() {
                $('#currentlyWearing').append(`
                <div class="cell small-6 medium-3 text-center">
                    <div class="character-item">
                        <a href="/market/${this.id}" target="_blank">
                            <img src="${this.thumbnail_url}" style="width:100%;">
                        </a>
                        <div class="push-5"></div>
                        <div class="mb-5" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <a href="/market/${this.id}" target="_blank">${this.name}</a>
                        </div>
                        <button onclick="update('unwear', '${this.type}')" class="button button-red character-button">Unwear</button>
                        </div>
                    </div>
                    <div class="push-15"></div>
                </div>`);
            });
        }
    }).fail(function() {
        $('#currentlyWearing').removeClass('grid-x grid-margin-x').html('Unable to retrieve items. Please refresh.');
    });
}

function getInventory(category, page)
{
    $.get('/character/inventory', {
        category: category,
        page: page,
        characterPage: true
    }).done(function(data) {
        $('#inventory').html('');
        $('#inventoryButtons').html('');

        if (typeof data.success !== 'undefined' && !data.success) {
            $('#inventory').removeClass('grid-x grid-margin-x').html(`
            <div class="text-center">
                <h1><i class="icon icon-sad"></i></h1>
                <h5>${data.message}</h5>
            </div>`);
        } else {
            $('#inventory').addClass('grid-x grid-margin-x');

            $.each(data.items, function() {
                $('#inventory').append(`
                <div class="cell small-6 medium-3 text-center">
                <div class="character-item">
                    <a href="/market/${this.id}" target="_blank">
                        <img src="${this.thumbnail_url}" style="width:100%;">
                    </a>
                    <div class="push-5"></div>
                    <div class="mb-5" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <a href="/market/${this.id}" target="_blank">${this.name}</a>
                    </div>
                    <button onclick="update('wear', ${this.id})" class="button button-green character-button">Wear</button>
                </div>
                <div class="push-15"></div>
            </div>`);
            });

            if (data.total_pages > 1) {
                var previousDisabled = (data.current_page == 1) ? 'disabled' : '';
                var nextDisabled = (data.current_page == data.total_pages) ? 'disabled' : '';
                var previousPage = data.current_page - 1;
                var nextPage = data.current_page + 1;

                $('#inventoryButtons').html(`
                <div class="cell small-12 text-center">
                    <button onclick="getInventory('${currentCategory}', ${previousPage})" class="button button-red" ${previousDisabled}>Previous</button>
                    <span style="margin-left:5px;margin-right:5px;">${data.current_page} of ${data.total_pages}</span>
                    <button onclick="getInventory('${currentCategory}', ${nextPage})" class="button button-green" ${nextDisabled}>Next</button>
                </div>`);
            }
        }
    }).fail(function() {
        $('#inventory').removeClass('grid-x grid-margin-x').html('Unable to retrieve items. Please refresh.');
    });
}
