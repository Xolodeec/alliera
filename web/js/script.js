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

    /*function getCounterDocuments()
    {
        $.ajax({
            type: 'GET',
            url: '/site/get-counter-document',
            success: function (counter){
                if(counter > 0){
                    $('.counter-document span').text('(' + counter + ')');
                }
            },
        });
    }

    function resetCounterDocuments()
    {
        $.ajax({
            type: 'GET',
            url: '/site/reset-counter-document',
            success: function (counter){
                if(counter > 0){
                    $('.counter-document span').text('(' + counter + ')');
                }
            },
        });
    }

    $(document).on('click', '.counter-document', function (){
        resetCounterDocuments();
    });

    getCounterDocuments();*/
});