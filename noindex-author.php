<?php

/**
 * Plugin Name: Noindex Author
 * Plugin URI: https://wordpress.org/plugins/noindex-author/
 * Description: Simply exclude all author posts from indexing it in SERPs.
 * Version: 1.1
 * Author: Sirius Pro
 * Author URI: https://siriuspro.pl
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

//Noindex Author

add_action( 'admin_menu', 'sp_noindex_author_options_page' );

function sp_noindex_author_options_page() {

	add_options_page(
		'Noindex Author', // page <title>Title</title>
		'Noindex Author', // menu link text
		'manage_options', // capability to access the page
		'sp_noindex_author-slug', // page URL slug
		'sp_noindex_author_page_content', // callback function with content
		2 // priority
	);

}


function sp_noindex_author_page_content(){

	echo '<div class="wrap">
	<h1>Noindex Author</h1>
	<form method="post" action="options.php">';
			
		settings_fields( 'sp_noindex_author_settings' ); // settings group name
		do_settings_sections( 'sp_noindex_author-slug' ); // just a page slug
		submit_button();

	echo '</form></div>';

}

add_action( 'admin_init',  'sp_noindex_author_register_setting' );

function sp_noindex_author_register_setting(){

	register_setting(
		'sp_noindex_author_settings', // settings group name
		'authors_ids', // option name
		'sanitize_text_field' // sanitization function
	);

	add_settings_section(
		'sp_noindex_author_section_id', // section ID
		'', // title (if needed)
		'', // callback function (if needed)
		'sp_noindex_author-slug' // page slug
	);

	add_settings_field(
		'authors_ids',
		'Enter author ID',
/**		'Enter authors ID\'s with comas', **/
		'sp_noindex_author_text_field_html', // function which prints the field
		'sp_noindex_author-slug', // page slug
		'sp_noindex_author_section_id', // section ID
		array( 
			'label_for' => 'authors_ids',
			'class' => 'sp_noindex_author-class', // for <tr> element
		)
	);

}

function sp_noindex_author_text_field_html(){

	$text = get_option( 'authors_ids' );

	printf(
		'<input type="text" id="authors_ids" name="authors_ids" value="%s" placeholder="1,2,3 etc."/>',
		esc_attr( $text )
	);

}

add_action('wp_head','sp_noindex_author_meta', -1000);

function sp_noindex_author_meta(){
    if ( is_single() ) {
        $post_author_id = get_post_field( 'post_author', get_the_ID() );
   		$social_options = get_option( 'authors_ids' );

        if ( $social_options == $post_author_id ) : ?>
            <meta name="robots" content="noindex, nofollow">
        <?php endif;
  } 
}