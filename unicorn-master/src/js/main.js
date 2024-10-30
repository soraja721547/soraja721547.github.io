var swiper = new Swiper(".swiper-cards-container", {
  loop: true,
  slidesPerView: 2.05,
  centeredSlides: true,
  spaceBetween: 0,
  pagination: {
    el: ".swiper-cards-pagination",
    clickable: true
  }
});
// ************cards-m***********
var swiper = new Swiper(".m-cards-container", {
  loop: true,
  slidesPerView: 2,
  centeredSlides: true,
  spaceBetween: 30,
  pagination: {
    el: ".m-cards-pagination",
    dynamicBullets: true
  }
});
// ************cards-m************
var swiper1 = new Swiper(".swiper-school-img", {
  slidesPerView: 1,
  spaceBetween: 20,
  loop: true,
  // autoHeight: true,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev"
  }
});

var swiper2 = new Swiper(".swiper-school-content", {
  slidesPerView: 1,
  loop: true,
  pagination: {
    el: ".swiper-school-pagination",
    dynamicBullets: true
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev"
  }
});

swiper2.on("slideNextTransitionStart", function() {
  swiper1.slideNext(300);
});

swiper2.on("slidePrevTransitionStart", function() {
  swiper1.slidePrev(300);
});

var swiperVerticalBall = new Swiper(".swiper-vertical-ball", {
  direction: "vertical",
  slidesPerView: 2.2,
  centeredSlides: true,
  loop: true,
  spaceBetween: 0,
  // mousewheel: true,
  pagination: {
    el: ".swiper-vertical-pagination",
    dynamicBullets: true
    // clickable: true,
  }
});

var swiperVerticalContent = new Swiper(".swiper-vertical-content", {
  direction: "vertical",
  slidesPerView: 1,
  centeredSlides: true,
  loop: true,
  spaceBetween: 0
  // mousewheel: true,
});

swiperVerticalBall.on("slideNextTransitionStart", function() {
    swiperVerticalContent.slideNext(300);
});

swiperVerticalBall.on("slidePrevTransitionStart", function() {
  swiperVerticalContent.slidePrev(300);
});
// *************vertical-m********************
var mVerticalBall = new Swiper(".m-vertical-ball", {
  // direction: "vertical",
  slidesPerView: 3.9,
  centeredSlides: true,
  loop: true,
  spaceBetween: 0,
  // mousewheel: true,
  pagination: {
    el: ".m-vertical-pagination",
    dynamicBullets: true
    // clickable: true,
  }
});

var mVerticalContent = new Swiper(".m-vertical-content", {
  // direction: "vertical",
  slidesPerView: 1,
  centeredSlides: true,
  loop: true,
  spaceBetween: 0
  // mousewheel: true,
});

mVerticalBall.on("slideNextTransitionStart", function() {
    mVerticalContent.slideNext(300);
});

mVerticalBall.on("slidePrevTransitionStart", function() {
  mVerticalContent.slidePrev(300);
});
// ****************vertical-m********************

// ************message*************
  $(document).ready(function(){
    $(".MessageReadMore").on('show.bs.modal', function() {
        setTimeout(function() {
            var swiper = new Swiper(".swiper-message-container", {
                // spaceBetween: 30,
                pagination: {
                  el: '.swiper-message-pagination',
                  clickable: true,
                },
              });
        }, 500);
    });
});
// ************message*************

// *****************************************************************************************************************11-30-整合
var swiper = new Swiper(".swiper-banner-container", {
  loop:true,
  pagination: {
    el: ".swiper-pagination"
  }
});



//**********************navbar捲軸事件*********************************/
var screenTop;
var nav = document.getElementById("navBar")

window.onscroll = function(){
var screenTop = document.documentElement.scrollTop;
  if(screenTop > 62){
  nav.classList.add("fixed")}
  else{
  nav.classList.remove("fixed")
  };
}
// **************google maps*************

var tableMap;
    function initMap() {
      tableMap = new google.maps.Map(document.getElementById('gooMapTable'), {
        center: {lat: 35.658276, lng: 139.728525},
        zoom: 18,
        styles:[
          {
              "stylers": [
                  {
                      "saturation": -100
                  },
                  {
                      "gamma": 0.8
                  },
                  {
                      "lightness": 4
                  },
                  {
                      "visibility": "on"
                  }
              ]
          },
          {
              "featureType": "landscape.natural",
              "stylers": [
                  {
                      "visibility": "on"
                  },
                  {
                      "color": "#5dff00"
                  },
                  {
                      "gamma": 4.97
                  },
                  {
                      "lightness": -5
                  },
                  {
                      "saturation": 100
                  }
              ]
          }
      ]
      });
    var marker = new google.maps.Marker({
      position : {lat: 35.658276, lng: 139.728525},
      map:tableMap,
      title:'Unicorn Kids International School'
    })

    initMap2();

    }

// **************************手機板maps*************************


    var phoneMap;
    function initMap2() {
      phoneMap = new google.maps.Map(document.getElementById('gooMapPhone'), {
        center: {lat: 35.658276, lng: 139.728525},
        zoom: 18,
        styles:[
          {
              "stylers": [
                  {
                      "saturation": -100
                  },
                  {
                      "gamma": 0.8
                  },
                  {
                      "lightness": 4
                  },
                  {
                      "visibility": "on"
                  }
              ]
          },
          {
              "featureType": "landscape.natural",
              "stylers": [
                  {
                      "visibility": "on"
                  },
                  {
                      "color": "#5dff00"
                  },
                  {
                      "gamma": 4.97
                  },
                  {
                      "lightness": -5
                  },
                  {
                      "saturation": 100
                  }
              ]
          }
      ]
      });
    var marker = new google.maps.Marker({
      position : {lat: 35.658276, lng: 139.728525},
      map:phoneMap,
      title:'Unicorn Kids International School'
    })
    }