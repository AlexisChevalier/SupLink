var waitShorten = false;

//Gestion de l'ajax sur la génération d'URL
$(document).ready(function () {
    $('#shortenerForm').submit(function () {
        if(waitShorten==false){
            waitShorten = true;
            var url = $('#shortenerForm').find('#url_long').val();
            $.ajax({
                url:site_url + "ajaxActions/urlShorten",
                type:"post",
                data:{ url:url},
                success:function (data) {
                    console.log(data);
                    if (data != 'err_url_invalid') {
                        $('#shortenerForm').hide('slow', function () {
                            $('.message').remove();
                            $('#shortener').html('<div class="just_shortened">Your URL : <a target="_blank" href="' + data + '">' + data + '</a><br/><a class="statLink" href="' + data + '/stats">Click here for the statistics</a></div>' +
                                '<div class="just_shortened_info">If you are logged, please refresh the page to see the url in the list below.</div>');
                        });
                        waitShorten = false;
                    } else {
                        $('.message').remove();
                        $('header').append("<div id='messages'><div class='message'>Wrong url !</div></div>");
                        waitShorten = false;
                    }
                }
            });
            return false;
        }
    });
});
