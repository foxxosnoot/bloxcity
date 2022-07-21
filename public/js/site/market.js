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

var currentHeader;
var currentPage;
var currentCategory;
var currentSearch;

$(function() {
    switchCategory('recent', 1);
    switchCategory('featured', 1, '', true);

    $('#load-more').click(function() {
        currentPage++;

        switchCategory(currentCategory, currentPage);
    });

    $('#category-selector').change(function() {
        currentPage = 1;
        switchCategory(this.value, 1, currentSearch);
    });

    $('#search').keypress(function(e) {
        if (e.which == 13) {
            currentPage = 1;
            switchCategory(currentCategory, currentPage, $('#search').val());
        }
    });
});

function switchCategory(category, page, search, isFeatured)
{
    if (typeof search === 'undefined') search = '';
    if (typeof isFeatured === 'undefined') isFeatured = false;

    if (!isFeatured) {
        currentCategory = category;
        currentPage = page;
        currentSearch = search;
    }

    var elem = (!isFeatured) ? '#items' : '#featured';
    var elem2 = (!isFeatured) ? 'cell small-6 medium-2' : 'swiper-slide';

    $.get('/api/v1/market/main', {
        category: category,
        page: page,
        search: search
    }).done(function(data) {
        if (page <= 1) $(elem).empty();
        if (!isFeatured) $(".market-load-more").hide();

        if (!isFeatured) {
            switch (currentCategory) {
                case 'recent': newHeader = 'Recent'; break;
                case 'heads': newHeader = 'Heads'; break;
                case 'hats': newHeader = 'Hats'; break;
                case 'faces': newHeader = 'Faces'; break;
                case 'accessories': newHeader = 'Accessories'; break;
                case 't-shirts': newHeader = 'T-Shirts'; break;
                case 'shirts': newHeader = 'Shirts'; break;
                case 'pants': newHeader = 'Pants'; break;
                case 'sets': newHeader = 'Sets'; break;
                default: newHeader = 'Unknown Category'; break;
            }

            $('#header').text(newHeader);

            if (search != '') {
                $('#results-for').show().html(`Search results for "<strong>${search}</strong>"`);
            } else {
                $('#results-for').hide().empty();
            }
        }

        if (typeof data.success !== 'undefined' && !data.success) {
            if (!isFeatured) $(elem).removeClass('grid-x grid-margin-x').text(data.message);
            else $(elem).html(data.message);
        } else {
            if (!isFeatured) $(elem).addClass('grid-x grid-margin-x');

            $.each(data.data, function() {
                var priceHtml = '';
                var priceOptionsHtml = '';
                var collectibleText = '';

                if (this.onsale) {
                    if (this.price_coins > 0) {
                        priceOptionsHtml += `
                        <div class="market-item-price-coins">
                            <i class="icon icon-coins"></i> ${this.price_coins}
                        </div>`;
                    }

                    if (this.price_cash > 0) {
                        priceOptionsHtml += `
                        <div class="market-item-price-cash">
                            <i class="icon icon-cash"></i> ${this.price_cash}
                        </div>`;
                    }

                    priceHtml = `
                    <div class="market-item-price">
                        ${priceOptionsHtml}
                    </div>`;
                }

                if (this.collectible) {
                    collectibleText = (this.collectible_stock <= 0) ? 'Sold Out' : `${this.collectible_stock} remaining`;

                    priceHtml += `
                    <div class="market-item-stock">
                        ${collectibleText}
                    </div>`;
                }

                $(elem).append(`
                <div class="${elem2} market-item-cell">
                    <a href="/market/${this.id}" title="${this.name}">
                        <img class="market-item-thumbnail" src="${this.thumbnail_url}">
                    </a>
                    <a href="/market/${this.id}" class="market-item-name" title="${this.name}">${this.name}</a>
                    <div class="market-item-creator">Creator: <a href="/profile/${this.creator.username}">${this.creator.username}</a></div>
                    ${priceHtml}
                </div>`);
            });

            if (!isFeatured) {
                if (data.has_next_page) {
                    $(".market-load-more").show();
                } else {
                    $(".market-load-more").hide();
                }
            }

            if (isFeatured) initSwiper();
        }
    }).fail(function() {
        $(elem).removeClass('grid-x grid-margin-x').text('Unable to retrieve items. Please refresh.');
    });
}
