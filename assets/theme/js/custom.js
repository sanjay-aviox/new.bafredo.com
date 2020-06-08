$(document).ready(function(){
	// navsection show on scroll
	var header = $(".header-section");
	$(window).scroll(function() {
	    var scroll = $(window).scrollTop();
	    if (scroll >= 100) {
	        header.addClass("scroll-navbar");
	    } else {
	        header.removeClass("scroll-navbar");
	    }
	});

	// scrolltop show on refresh
	$(window).load(function() {
	var scroll = $(window).scrollTop();
	    if (scroll >= 100) {
	        header.addClass("scroll-navbar");
	    } else {
	        header.removeClass("scroll-navbar");
	    }
	});

	// top slider js
	$('.slider-section').owlCarousel({
	    loop:true,
	    nav:true,
	    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
	    autoplay:true,
	    dots:false,
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:1
	        },
	        1000:{
	            items:1
	        }
	    }
	});

	// ourProductSlider
	$('.ourProductSlider').owlCarousel({
	    loop:true,
	    nav:true,
	    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
	    autoplay:true,
	    margin:20,
	    dots:true,
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:2
	        },
	        1000:{
	            items:3
	        }
	    }
	});

	// relatedProductSlider
	$('.relatedProductSlider').owlCarousel({
	    loop:true,
	    nav:true,
	    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
	    autoplay:true,
	    margin:20,
	    dots:true,
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:2
	        },
	        1000:{
	            items:4
	        }
	    }
	});

	// you-may-like-Slider
	$('.you-may-like-Slider').owlCarousel({
	    loop:true,
	    nav:true,
	    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
	    autoplay:true,
	    margin:20,
	    dots:true,
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:1
	        },
	        1000:{
	            items:1
	        }
	    }
	});
	

	// tooltip
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	});

});

// cart sidenav bar 
function openNav() {
	document.getElementById("mySidenav").style.right = "0px"; 
}
function closeNav() {
	document.getElementById("mySidenav").style.right = "-340px";
}

// js for zoom image
var demoTrigger = document.querySelector('.demo-trigger');
var paneContainer = document.querySelector('.product-demo-detail');

// new Drift(demoTrigger, {
//     paneContainer: paneContainer,
//     inlinePane: false,
// });