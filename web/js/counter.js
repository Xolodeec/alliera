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

    function getUnpaidDocuments()
    {
        $.ajax({
            type: 'GET',
            url: '/site/unpaid-documents',
            success: function (documents){
                if(Object.keys(documents).length > 0){

                    let html = "";

                    for (let fileName in documents){
                        let itemList = "<li><a href='" + documents[fileName] + "' target='_blank'>" + fileName + "</a></li>"

                        html = html + itemList;
                    }

                    $('.wrapper-unpaidDocument ul').html(html);
                }
            },
        });
    }

    $(document).on('click', '.counter-document', function (){
        resetCounterDocuments();
    });

    getCounterDocuments();
    getUnpaidDocuments();
});