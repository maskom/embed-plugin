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
    wp_enqueue_style( 'admin-css-bootstrap', plugins_url('/assets/css/bootstrap.min.css', __FILE__), array(), null, 'all' );
    wp_enqueue_script( 'admin-js', plugins_url('/assets/js/scripts.js', __FILE__) , array('jquery'), null, true );
    wp_localize_script( 'pva-js', 'pva_params', array( 'pva_ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'pva-js' );
}
function embed_url() { ?>
    <div class="wrap-page mt-3 mb-3">
        <div class="container">
            <h1><?php _e('Emded url', 'embed') ?></h1>
            <form class="mt-4 mb-4" id="embedUrl">
                <div class="form-row align-items-center">
                    <div class="col-sm-11 my-1">
                        <label class="sr-only" for="urlLink">Url/Link</label>
                        <input type="url" class="form-control" id="urlLink" name="urlLink" placeholder="Paste your http:// link here">
                    </div>
                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <hr>
            <form class="mt-4 mb-4" id="outputEmbed" action="" method="post">
                <h3><?php _e('Output', 'embed') ?></h3>
                <div class="form-row align-items-center">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                    </div>
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="url">Url</label>
                        <input type="text" class="form-control" id="url" name="url" placeholder="Url">
                    </div>
                </div>
                <div class="form-row align-items-center">
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="icon">icon</label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon">
                    </div>
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="site">Site</label>
                        <input type="text" class="form-control" id="site" name="site" placeholder="Site">
                    </div>
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="author">Author</label>
                        <input type="text" class="form-control" id="author" name="author" placeholder="Author">
                    </div>
                    <div class="form-group col-sm-3 my-1">
                        <label class="sr-only" for="date">Date</label>
                        <input type="text" class="form-control" id="date" name="date" placeholder="Date">
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="desription">Desription</label>
                        <textarea  class="form-control" id="desription" name="description" placeholder="Desription" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="thumbnail">Thumbnail Url</label>
                        <input type="url" class="form-control" id="thumbnail" name="thumbnail" placeholder="Thumbnail Url">
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="keywords">Keywords</label>
                        <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Keywords">
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-12 my-1">
                        <label class="sr-only" for="htmlEmbed">Html Embed</label>
                        <textarea  class="form-control" id="htmlEmbed" name="htmlEmbed" placeholder="Html Embed" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="col-md-6">
                        <div class="html"></div>
                    </div>
                </div>
                <div class="form-row align-items-center mt-4">
                    <div class="form-group col-sm-4 my-1">
                        <label class="sr-only" for="postType">Post Type</label>
                        <select id="postType" class="form-control">
                        <?php
                        $args = array(
                            'public'   => true,
                            '_builtin' => true
                        );
                        $output = 'names'; // names or objects, note names is the default
                        foreach ( get_post_types($args, $output) as $post_type ) {
                            ?>
                            <option value="<?php echo  $post_type ;?>"><?php echo  $post_type ;?></option>
                        <?php }
                        ?>
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

function pva_create() {
    $post_title = $_POST['title'];

    // Create post object
    $new_pva_post = array(
        'post_type'     => 'post',
        'post_title'    => $post_title,
        'post_status'   => 'publish',
        'post_author'   => 1,
    );

    // Insert the post into the database
    wp_insert_post( $new_pva_post );
    exit();
}

add_action('wp_ajax_pva_create','pva_create');