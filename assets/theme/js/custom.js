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
    $('.ourSlider').owlCarousel({
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
	            items:3
	        },
	        1000:{
	            items:5
	        }
	    }
	});

	// ourProductSlider
	$('.ourProductSlider').owlCarousel({
	    loop:false,
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
	    loop:false,
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
	    loop:false,
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
	
	// selector-product-Slider
	$('.selector-product-Slider').owlCarousel({
	    loop:false,
	    nav:true,
	    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
	    autoplay:false,
	    margin:2,
	    dots:false,
	    responsive:{
	        0:{
	            items:4
	        },
	        600:{
	            items:4
	        },
	        1000:{
	            items:4
	        }
	    }
	});

	// tooltip
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	});

	// mobile sidebar dropdown menu
	var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
      dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
          dropdownContent.style.display = "none";
        } else {
          dropdownContent.style.display = "block";
        }
      });
    }


});

// cart sidenav bar 
function openNav() {
	document.getElementById("mySidenav").style.right = "0px"; 
}
function closeNav() {
	document.getElementById("mySidenav").style.right = "-340px";
}