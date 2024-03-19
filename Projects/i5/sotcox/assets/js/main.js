(function ($) {
	"use strict";

	// service slider active
	var sotcoxService = $("[data-service-slider]");
	sotcoxService.owlCarousel({
		loop: true,
		margin: 0,
		loop: true,
		slideSpeed: 3000,
		nav: true,
		dots: false,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='far fa-arrow-right'></i>",
		],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				margin: 0,
			},
			768: {
				items: 2,
			},
			1200: {
				items: 3,
			},
			1440: {
				items: 4,
			},
		},
	});

	// Testimonia slider active
	var sotcoxTestimonial = $("[data-testimonial-slider]");
	sotcoxTestimonial.owlCarousel({
		loop: true,
		margin: 30,
		loop: true,
		slideSpeed: 3000,
		nav: true,
		dots: false,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='far fa-arrow-right'></i>",
		],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				margin: 0,
			},
			992: {
				items: 2,
			},
			1440: {
				items: 3,
			},
		},
	});

	var sotcoxTestimonial2 = $("[data-testimonial-slider-2]");
	sotcoxTestimonial2.owlCarousel({
		loop: true,
		margin: 20,
		loop: true,
		slideSpeed: 3000,
		nav: false,
		dots: false,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='far fa-arrow-right'></i>",
		],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				margin: 0,
			},
			992: {
				items: 2,
			},
			1440: {
				items: 3,
			},
		},
	});

	// project slider active
	var sotcoxProjectCarousel = $("[data-project-carousel]");
	sotcoxProjectCarousel.owlCarousel({
		loop: true,
		margin: 30,
		loop: true,
		slideSpeed: 3000,
		nav: true,
		dots: false,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='far fa-arrow-right'></i>",
		],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				margin: 0,
			},
			992: {
				items: 2,
			},
		},
	});

	// article slider active
	var articleCarousel = $("[data-article-carousel]");
	articleCarousel.owlCarousel({
		loop: true,
		margin: 30,
		loop: true,
		slideSpeed: 3000,
		nav: true,
		dots: false,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='far fa-arrow-right'></i>",
		],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				margin: 0,
			},
			992: {
				items: 2,
			},
		},
	});

	// team slider active
	var teamSlider = $("[data-team-slider]");
	teamSlider.owlCarousel({
		loop: true,
		margin: 30,
		loop: true,
		slideSpeed: 3000,
		nav: true,
		dots: false,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='far fa-arrow-right'></i>",
		],
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				margin: 0,
			},
			576: {
				items: 2,
			},
			992: {
				items: 3,
			},
			1440: {
				items: 4,
			},
		},
	});

	// post slider active js
	var postGallery = $("[data-sotcox-post-gallery]");
	postGallery.owlCarousel({
		loop: true,
		margin: 0,
		loop: true,
		slideSpeed: 3000,
		nav: true,
		dots: false,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='fal fa-arrow-right'></i>",
		],
		responsiveClass: true,
		items: 1,
	});

	// post slider active js
	var portfolioDetailsCarousel = $("[data-portfolio-details-carousel]");
	portfolioDetailsCarousel.owlCarousel({
		loop: true,
		margin: 30,
		loop: true,
		slideSpeed: 3000,
		nav: false,
		dots: true,
		navText: [
			"<i class='far fa-arrow-left'></i>",
			"<i class='fal fa-arrow-right'></i>",
		],
		responsiveClass: true,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				margin: 0,
			},
			576: {
				items: 2,
			},
			992: {
				items: 3,
			}
		}
	});

	// copyright year
	function copyRightYear() {
		let currentYear = new Date().getFullYear();
		document.getElementById("copyright-date").innerHTML = currentYear;
	}
	copyRightYear();

	// back to top active js
	$("[data-sotcox-backtotop]").on("click", function () {
		$("html, body").animate(
			{
				scrollTop: 0,
			},
			1000
		);
	});

	// toggle active class on hover on service slider
	$(".sotcox-workflow-box").on("mouseenter", function () {
		$(".sotcox-workflow-box").removeClass("active");
		$(this).addClass("active");
	});

	// project filter active
	var $grid = $(".sotcox-project-grid");

	var filterFns = {};

	$(".sotcox-project-filter").on("click", "li", function () {
		var filterValue = $(this).attr("data-filter");
		filterValue = filterFns[filterValue] || filterValue;
		$grid.isotope({
			filter: filterValue,
		});
	});

	// change is-checked class on buttons
	$(".sotcox-project-filter").each(function (i, buttonGroup) {
		var $buttonGroup = $(buttonGroup);
		$buttonGroup.on("click", "li", function () {
			$buttonGroup.find(".active").removeClass("active");
			$(this).addClass("active");
		});
	});

	// search popup active
	if ($("[data-search-trigger]").length) {
		$("[data-search-trigger]").on("click", function () {
			$("body").addClass("search-active");
		});
		$(".close-search").on("click", function () {
			$("body").removeClass("search-active");
		});

		$(".search-popup .color-layer").on("click", function () {
			$("body").removeClass("search-active");
		});
	}

	// side info active
	$("[data-sideinfo-trigger]").on("click", function (e) {
		e.preventDefault();
		$(".side-info-wrapper").addClass("is-open");
		$(".sotcox-overlay").addClass("is-open");
	});

	// remove active class from mobile-menu
	$(".side-info__close, .sotcox-overlay").on("click", function (e) {
		e.preventDefault();
		$(".sotcox-overlay").removeClass("is-open");
		$(".side-info-wrapper").removeClass("is-open");
	});

	// background image js
	function background() {
		var img = $(".bg_img");
		img.css("background-image", function () {
			var bg = "url(" + $(this).data("background") + ")";

			if ($(this).data("background")) {
				return bg;
			} else {
				return false;
			}
		});
	}

	$(function () {
		$(".sotcox-btn")
			.on("mouseenter", function (e) {
				var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$(this).find("span").css({ top: relY, left: relX });
			})
			.on("mouseout", function (e) {
				var parentOffset = $(this).offset(),
					relX = e.pageX - parentOffset.left,
					relY = e.pageY - parentOffset.top;
				$(this).find("span").css({ top: relY, left: relX });
			});
	});

	// active mobile-menu
	jQuery("#sotcox-navbar").meanmenu({
		meanScreenWidth: "1199",
		meanMenuContainer: ".sotcox-mobile-menu",
	});

	// Active Odometer Counter
	jQuery(".odometer").appear(function (e) {
		var odo = jQuery(".odometer");
		odo.each(function () {
			var countNumber = jQuery(this).attr("data-count");
			jQuery(this).html(countNumber);
		});
	});

	// Active wow js
	new WOW().init();

	// Activate lightcase
	$("a[data-rel^=lightcase]").lightcase();

	// add class on button
	$("a.sotcox-btn").each(function () {
		if ($(this).html().indexOf("<span>") >= 0) {
			$(this).addClass("has-span");
		} else {
			$(this).addClass("no-span");
		}
	});

	// skill bar active
	if (typeof ($.fn.knob) != 'undefined') {
		$('.knob').each(function () {
			var $this = $(this),
				knobVal = $this.attr('data-rel');

			$this.knob({
				'draw': function () {
					$(this.i).val(this.cv + '%')
				}
			});

			$this.appear(function () {
				$({
					value: 0
				}).animate({
					value: knobVal
				}, {
					duration: 2000,
					easing: 'swing',
					step: function () {
						$this.val(Math.ceil(this.value)).trigger('change');
					}
				});
			}, {
				accX: 0,
				accY: -150
			});
		});
	}

	// preloader active
	function preloader() {
        if($('#sotcox-loadding').length) {
            $('#sotcox-loadding').delay(300).fadeOut(500, function() {});
        }
    }

	// WHEN DOCUMENT LOADING
	$(window).on("load", function () {
		preloader();
		background();
	});
})(jQuery);
