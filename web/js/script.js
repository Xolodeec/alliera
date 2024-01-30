$(document).ready(function (){
    $('.first-button').on('click', function () {
        $('.animated-icon1').toggleClass('open');
        $('.collapse').toggleClass('show');
    });

    var rb = $(".dropbtn-profile");
    var rw = $(".wrapper-profile");
    rb.click(function() {
        rw.toggleClass('open'); /* <-- toggle the application of the open class on click */
    });
});