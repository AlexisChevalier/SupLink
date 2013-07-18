var wait = false;

$(document).ready(function () {
    $("#listUrls").stupidtable();
    $('.nameSpan').editable(site_url + 'ajaxActions/editUrl');
    $('.editIcon').click(function () {
        $(this).next().click();
    });

    //Désactivation d'url
    $('.optionsTd').on('click', '.disable', function () {
        if(wait == false){
            wait = true;
            var id = $(this).parent().parent().attr('id');
            $.ajax({
                url:site_url + "ajaxActions/disableUrl",
                type:"post",
                data:{ id:id},
                success:function (data) {
                    if (data != 'err_disable_failed') {
                        $('#' + id).removeClass('enabled');
                        $('#' + id).addClass('disabled');
                        $('#' + id).find('.disable').remove();
                        $('#' + id).find('.optionsTd').append('<img title="Enable" class="enable optionIcon" src="http://webprojet.fr/assets/img/enable.png">');
                        wait = false;
                    } else {
                        wait = false;
                        alert("Delete failed. Please refresh the page !");
                    }
                }
            });
        }
    });

    //Activation d'url
    $('.optionsTd').on('click', '.enable', function () {
        if(wait == false){
            wait = true;
            var id = $(this).parent().parent().attr('id');
            $.ajax({
                url:site_url + "ajaxActions/enableUrl",
                type:"post",
                data:{ id:id},
                success:function (data) {
                    if (data != 'err_enable_failed') {
                        $('#' + id).removeClass('disabled');
                        $('#' + id).addClass('enabled');
                        $('#' + id).find('.enable').remove();
                        $('#' + id).find('.optionsTd').append('<img title="Disable" class="disable optionIcon" src="http://webprojet.fr/assets/img/disable.png">');
                        wait = false;
                    } else {
                        alert("Delete failed. Please refresh the page !");
                        wait = false;
                    }
                }
            });
        }
    });

    //Suppression d'url
    $('.delete').click(function () {
        if(wait == false){
            wait = true;
            var id = $(this).parent().parent().attr('id');
            $.ajax({
                url:site_url + "ajaxActions/deleteUrl",
                type:"post",
                data:{ id:id},
                success:function (data) {
                    if (data != 'err_delete_failed') {
                        $('#' + id).hide('slow', function () {
                            $('#' + id).remove();
                            if ($('#listUrls').find("tbody").find('tr').length == 0) {
                                $('#wrapper').hide('slow', function () {
                                    $('#wrapper').html("Welcome on HeavenShortener ! You can now start to shorten and use some urls !");
                                    $("#wrapper").show('slow');
                                });
                            }
                            wait = false;

                        });
                    } else {
                        wait = false;
                        alert("Delete failed. Please refresh the page !");
                    }
                }
            });
        }
    });
});
