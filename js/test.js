var elements = $('.modal-overlay, .modal');

$('.menu-toggle').click(function(){
    elements.addClass('active');
});

$('.close-modal').click(function(){
    elements.removeClass('active');
});


function disableScroll() {
    document.body.classList.add("disable-scrolling");
 }
function enableScroll() {
   document.body.classList.remove("disable-scrolling");
}