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

add_action( 'admin_enqueue_scripts', 'enqueue_admin_style_sheet' );
function enqueue_admin_style_sheet() {
    wp_enqueue_script( 'admin-js', plugins_url('/assets/js/scripts.js', __FILE__) , array('jquery'), null, true );
}

add_action( 'add_meta_boxes', 'metabox_aff' );
function metabox_aff() {
    add_meta_box(
        'embed_detail_box',
        'Embed output',
        'embed_detail_box',
        'post',
        'normal',
        'high'
    );
}
function embed_detail_box () {

    global $post;
    $values = get_post_custom( $post->ID );
    $title = isset( $values['title_embed'] ) ? esc_attr( $values['title_embed'][0] ) : '';
    $url = isset( $values['url_embed'] ) ? esc_attr( $values['url_embed'][0] ) : '';
    $icon = isset( $values['icon_embed'] ) ? esc_attr( $values['icon_embed'][0] ) : '';
    $site = isset( $values['site_embed'] ) ? esc_attr( $values['site_embed'][0] ) : '';
    $author = isset( $values['author_embed'] ) ? esc_attr( $values['author_embed'][0] ) : '';
    $date= isset( $values['date_embed'] ) ? esc_attr( $values['date_embed'][0] ) : '';
    $description= isset( $values['description_embed'] ) ? esc_attr( $values['description_embed'][0] ) : '';
    $thumbnail= isset( $values['thumbnail_embed'] ) ? esc_attr( $values['thumbnail_embed'][0] ) : '';
    $keywords= isset( $values['keywords_embed'] ) ? esc_attr( $values['keywords_embed'][0] ) : '';
    $htmlEmbed= isset( $values['htmlEmbed'] ) ? esc_attr( $values['htmlEmbed'][0] ) : '';

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'embed_meta_box_nonce', 'meta_box_nonce' )

    ?>
    <input type="url" class="regular-text" id="urlLink" name="urlLink" placeholder="<?php esc_attr_e( 'Paste your http:// link here', 'EmbedUrl' ); ?>">
    <button type="button" class="button button-secondary button-large" id="submitUrl"><?php esc_attr_e( 'Embed', 'EmbedUrl' ); ?></button>
    <div id="detail-box">
        <br/><hr>
        <strong><h2><?php esc_attr_e( 'Detail:', 'EmbedUrl' ); ?></h2></strong>
        <hr><br/>
        <label for="title"><strong><?php esc_attr_e( 'Title', 'EmbedUrl' ); ?></strong></label>
        <input type="text" class="large-text" id="title" name="title" value="<?php echo $title; ?>" placeholder="Title">

        <label for="url"><strong><?php esc_attr_e( 'Url', 'EmbedUrl' ); ?></strong></label>
        <input type="text" class="large-text" id="url" name="url" value="<?php echo $url; ?>" placeholder="Url">

        <label for="icon"><strong><?php esc_attr_e( 'Icon', 'EmbedUrl' ); ?></strong></label>
        <input type="text" class="regular-text" id="icon" name="icon" value="<?php echo $icon; ?>" placeholder="Icon"><br>

        <label for="site"><strong><?php esc_attr_e( 'Site', 'EmbedUrl' ); ?></strong></label>
        <input type="text" class="regular-text" id="site" name="site" value="<?php echo $site; ?>"  placeholder="Site"><br>

        <label for="author"><strong><?php esc_attr_e( 'Author', 'EmbedUrl' ); ?></strong></label>
        <input type="text" class="regular-text" id="author" name="author" value="<?php echo $author; ?>" placeholder="Author"><br>

        <label for="date"><strong><?php esc_attr_e( 'Date', 'EmbedUrl' ); ?></strong></label>
        <input type="text" class="regular-text" id="date" name="date"  value="<?php echo $date; ?>" placeholder="Date"><br>

        <label for="description"><strong><?php esc_attr_e( 'Desription', 'EmbedUrl' ); ?></strong></label>
        <textarea  class="large-text" id="desription" name="description"  placeholder="Desription" rows="5"><?php echo $description; ?></textarea>

        <label for="thumbnail"><strong><?php esc_attr_e( 'Thumbnail Url', 'EmbedUrl' ); ?></strong></label>
        <input type="url" class="large-text" id="thumbnail" name="thumbnail" value="<?php echo $thumbnail; ?>" placeholder="Thumbnail Url">

        <label for="keywords"><strong><?php esc_attr_e( 'Keywords', 'EmbedUrl' ); ?></strong></label>
        <input type="text" class="large-text" id="keywords" name="keywords" value="<?php echo $keywords; ?>" placeholder="Keywords">

        <label for="htmlEmbed"><strong><?php esc_attr_e( 'Html Embed', 'EmbedUrl' ); ?></strong></label>
        <textarea  class="large-text" id="htmlEmbed" name="htmlEmbed"   placeholder="Html Embed" rows="4"><?php echo $htmlEmbed; ?></textarea>
    </div>


    <style>
        #embed_detail_box input, #embed_detail_box textarea {
           margin: 5px 0px;
        }
        #embed_detail_box label {
            padding: 5px 0px;
            width: 100%;
            display: block;
        }
    </style>
    <?php
}

add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'embed_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );

    // Make sure your data is set before trying to save it
    if( isset( $_POST['title'] ) )
        update_post_meta( $post_id, 'title_embed', wp_kses( $_POST['title'], $allowed ) );
    if( isset( $_POST['url'] ) )
        update_post_meta( $post_id, 'url_embed', wp_kses( $_POST['url'], $allowed ) );
    if( isset( $_POST['icon'] ) )
        update_post_meta( $post_id, 'icon_embed', wp_kses( $_POST['icon'], $allowed ) );
    if( isset( $_POST['site'] ) )
        update_post_meta( $post_id, 'site_embed', wp_kses( $_POST['site'], $allowed ) );
    if( isset( $_POST['author'] ) )
        update_post_meta( $post_id, 'author_embed', wp_kses( $_POST['author'], $allowed ) );
    if( isset( $_POST['author'] ) )
        update_post_meta( $post_id, 'author_embed', wp_kses( $_POST['author'], $allowed ) );
    if( isset( $_POST['date'] ) )
        update_post_meta( $post_id, 'date_embed', wp_kses( $_POST['date'], $allowed ) );
    if( isset( $_POST['description'] ) )
        update_post_meta( $post_id, 'description_embed', wp_kses( $_POST['description'], $allowed ) );
    if( isset( $_POST['thumbnail'] ) )
        update_post_meta( $post_id, 'thumbnail_embed', wp_kses( $_POST['thumbnail'], $allowed ) );
    if( isset( $_POST['keywords'] ) )
        update_post_meta( $post_id, 'keywords_embed', wp_kses( $_POST['keywords'], $allowed ) );
    if( isset( $_POST['htmlEmbed'] ) )
        update_post_meta( $post_id, 'htmlEmbed',  $_POST['htmlEmbed'] );


}
