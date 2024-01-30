$(document).ready(function (){
    function getCounterDocuments()
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

    getCounterDocuments();
});