/**
 * Created by komar on 5/13/2018.
 */

jQuery( document ).ready(function($) {

    var frm = $('#embedUrl');
    var frmPost = $('#outputEmbed');
    //$('#outputEmbed').hide();
    frm.submit(function (e) {

        e.preventDefault();

        var formData = {
            'url'       : $('input[name=urlLink]').val(),
            'api_key'   : 'c5ba1315154f685e04b457'
        };

        $.ajax({
            type: 'GET',
            url: 'http://iframe.ly/api/iframely',
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode          : true,
            success: function (data) {
                console.log('Submission was successful.');
                //console.log(data['links']['thumbnail'][0]['href']);
                $('.html').html(data['html']);
                $('#outputEmbed').show();
                $('input[name=title]').val(data['meta']['title']);
                $('input[name=url]').val(data['url']);
                $('input[name=icon]').val(data['links']['icon'][0]['href']);
                $('input[name=site]').val(data['meta']['site']);
                $('input[name=author]').val(data['meta']['author']);
                $('input[name=date]').val(data['meta']['date']);
                $('textarea[name=description]').val(data['meta']['description']);
                $('input[name=thumbnail]').val(data['links']['thumbnail'][0]['href']);
                $('input[name=keywords]').val(data['meta']['keywords']);
                $('textarea[name=htmlEmbed]').val(data['html']);

            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });
    // output
    frmPost.submit(function (e) {
        e.preventDefault();

        var pva_ajax_url = pva_params.pva_ajax_url;

        $.ajax({
            type: 'POST',
            url: pva_ajax_url,
            data: {
                action: 'embed_create',
                //post_title: returnNewPostTitle()
            },
            beforeSend: function ()
            {
                console.log('sending');
            },
            success: function(data)
            {
                console.log('yay');
            },
            error: function()
            {
                console.log('nay');

            }
        })
    })

});