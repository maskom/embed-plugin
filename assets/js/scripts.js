/**
 * Created by komar on 5/13/2018.
 */

jQuery( document ).ready(function($) {
    var api_key = php_vars.iframelyApiKey;
    var frm = $('#submitUrl');
    var htmlEmbed = $('#htmlEmbed').text();
    $('.html').html(htmlEmbed);
    frm.click(function (e) {
        e.preventDefault();

        var formData = {
            'url'       : $('input[name=urlLink]').val(),
            'api_key'   : api_key
        };

        $.ajax({
            type: 'GET',
            url: 'http://iframe.ly/api/iframely',
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode          : true,
            success: function (data) {
                console.log('Submission was successful.');
                //console.log(data);
                $('.html').html(data['html']);
                if (data['meta']['title']) {
                    $('input[name=title]').val(data['meta']['title']);
                }
                if(data['meta']['title']) {
                    $('input[name=post_title]').val(data['meta']['title']);
                }
                if(data['url']) {
                    $('input[name=url]').val(data['url']);
                }
                if(data['links']['icon']) {
                    $('input[name=icon]').val(data['links']['icon'][0]['href']);
                }
                if(data['meta']['site']) {
                    $('input[name=site]').val(data['meta']['site']);
                }
                if(data['meta']['author']) {
                    $('input[name=author]').val(data['meta']['author']);
                }
                if(data['meta']['date']) {
                    $('input[name=date]').val(data['meta']['date']);
                }
                if(data['meta']['description']) {
                    $('textarea[name=description]').val(data['meta']['description']);
                }
                if(data['links']['thumbnail']) {
                    $('input[name=thumbnail]').val(data['links']['thumbnail'][0]['href']);
                }
                if(data['meta']['keywords']) {
                    $('input[name=keywords]').val(data['meta']['keywords']);
                }
                if(data['html']) {
                    $('textarea[name=htmlEmbed]').val(data['html']);
                }

            },
            error: function (data) {
                console.log('An error occurred.');
                //console.log(data);
                $('.html').html(data['responseJSON']['error']);
                $('#detail-box').hide();
            },
        });


    });



});