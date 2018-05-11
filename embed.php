<?php
/*
Plugin Name: Embed
Plugin URI:  http://link to your plugin homepage
Description: Describe what your plugin is all about in a few short sentences
Version:     1.0
Author:      Your name (Yay! Here comes fame... )
Author URI:  http://link to your website
License:     GPL2 etc
License URI: http://link to your plugin license
*/

add_action('admin_menu', 'embed_plugin_setup_menu');

function embed_plugin_setup_menu() {
    $my_page = add_menu_page( 'Embed Url Page', 'Embed URL', 'manage_options', 'embed', 'embed_url' );

    add_action( 'load-' . $my_page, 'load_admin_scripts' );
}
function load_admin_scripts(){
    // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
    add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );
}
function enqueue_admin_scripts(){
    // Isn't it nice to use dependencies and the already registered core js files?
    wp_enqueue_style( 'admin-css-bootstrap', plugins_url('/assets/bootstrap.min.css', __FILE__), array(), null, 'all' );
  //  wp_enqueue_script( 'admin-init', plugins_url('/lib/js/admin.init.js', __FILE__) , array('jquery'), null, true );
}
function embed_url() { ?>
    <div class="wrap-page mt-3 mb-3">
        <div class="container">
            <h1>Embed Url</h1>
            <form class="mt-4 mb-4" id="embedUrl">
                <div class="form-row align-items-center">
                    <div class="col-sm-11 my-1">
                        <label class="sr-only" for="urlLink">Url/Link</label>
                        <input type="url" class="form-control" id="urlLink" placeholder="Paste your http:// link here">
                    </div>

                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <hr>

            <h3>Output</h3>
            <form class="mt-4 mb-4" id="outputEmbed">
                <div class="form-row align-items-center">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="title">Title</label>
                        <input type="text" class="form-control" id="title" placeholder="Title">
                    </div>
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="url">Url</label>
                        <input type="text" class="form-control" id="url" placeholder="Url">
                    </div>
                </div>
                <div class="form-row align-items-center">
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="icon">icon</label>
                        <input type="text" class="form-control" id="icon" placeholder="Icon">
                    </div>
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="site">Site</label>
                        <input type="text" class="form-control" id="site" placeholder="Site">
                    </div>
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="author">Author</label>
                        <input type="text" class="form-control" id="author" placeholder="Author">
                    </div>
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="date">Date</label>
                        <input type="text" class="form-control" id="date" placeholder="Date">
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="desription">Desription</label>
                        <textarea  class="form-control" id="desription" placeholder="Desription" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="thumbnail">Thumbnail Url</label>
                        <input type="url" class="form-control" id="thumbnail" placeholder="Thumbnail Url">
                    </div>
                    <div class="col-md-6 mt-3 my-1">
                        <img data-src="holder.js/100px250" class="img-fluid" alt="100%x250" style="height: 250px; width: 100%; display: block;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%221151%22%20height%3D%22250%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%201151%20250%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_1634eddaaa2%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A58pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_1634eddaaa2%22%3E%3Crect%20width%3D%221151%22%20height%3D%22250%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22408.515625%22%20y%3D%22150.8%22%3E1151x250%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="keywords">Keywords</label>
                        <input type="text" class="form-control" id="keywords" placeholder="Keywords">
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="htmlEmbed">Html Embed</label>
                        <textarea  class="form-control" id="htmlEmbed" placeholder="Html Embed" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-4 my-1">
                        <label class="sr-only" for="postType">Post Type</label>
                        <select id="postType" class="form-control">
                            <option selected>Choose...</option>
                            <option>...</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-4 my-1">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


<?php }