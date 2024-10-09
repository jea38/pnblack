const $body = $('body');
const $btnMenu = $('.menu-toggle');
$btnMenu.click(function () {
    $body.toggleClass('menu-open');
});

const btnCloseBar = document.querySelector('.js-close-bar');
const btnOpenBar = document.querySelector('.js-open-bar');
const searchBar = document.querySelector('.searchbar');


$(function () {
    $('.js-open-bar').on('click', function () {
        searchBar.classList.add('bar--is-visible');
    });
    $('.js-close-bar').on('click', function () {
        searchBar.classList.remove('bar--is-visible');
    });
});
