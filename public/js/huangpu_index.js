/**
 * Created by 鹏飞 on 2015/12/10 0010.
 */

var swiper = new Swiper('.swiper-container', {
    pagination: '.swiper-pagination',
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    slidesPerView: 1,
    paginationClickable: true,
    loop: true,
    visiblilityFullfit: true,
    autoplay: 5000,
});
$(document).ready(function(){
    $(".projText").dotdotdot();
});
