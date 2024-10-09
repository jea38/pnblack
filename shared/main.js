$(document).ready(function () {


	$('.hamburger').click(function () {

		var $this = $(this);

		if ($this.hasClass('is-active')) {
			$('.fsmenu, .logo').removeClass('is-active');
			$('.fsmenu, .logo').addClass('close-menu');
		} else {
			$('.fsmenu, .logo').removeClass('close-menu');
			$('.fsmenu, .logo').addClass('is-active');
		};
		$this.toggleClass('is-active');
	});

	$(".fsmenu--list-element").hover(
		function () {
			$(this).addClass('open');
			$(this).removeClass('is-closing');
		}, function () {
			$(this).removeClass('open');
			$(this).addClass('is-closing');
		}
	);

});



$(document).ready(function () {

    typing(0, $('.typewriter-text').data('text'));

    function typing(index, text) {

        var textIndex = 1;

        var tmp = setInterval(function () {
            if (textIndex < text[index].length + 1) {
                $('.typewriter-text').text(text[index].substr(0, textIndex));
                textIndex++;
            } else {
                setTimeout(function () {
                    backed(index, text)
                }, 2000);
                clearInterval(tmp);
            }

        }, 150);

    }

    function backed(index, text) {
        var textIndex = text[index].length;
        var tmp = setInterval(function () {

            if (textIndex + 1 > 0) {
                $('.typewriter-text').text(text[index].substr(0, textIndex));
                textIndex--;
            } else {
                index++;
                if (index == text.length) {
                    index = 0;
                }
                typing(index, text);
                clearInterval(tmp);
            }

        }, 150)

    }

});

var swiper = new Swiper(".section-4", {
    slidesPerView: 1.4,
    spaceBetween: 30,
    pagination: {
      el: ".custom-swiper-pagination",
      clickable: true
    },
    breakpoints: {
      550: {
        slidesPerView: 2
      },
      640: {
        slidesPerView: 3
      },
      1024: {
        slidesPerView: 4
      }
    }
  });