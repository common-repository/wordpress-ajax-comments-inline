<?php
/*
Plugin Name:Wordpress Ajax Comments Inline
Plugin URI: http://farlee.info/archives/wordpress-ajax-comments-inline-plugin.html
Author: Farlee
Author URI: htp://farlee.info/
Description: Display ajax comments inline for each post using jQuery,click to show or hide the ajax comments(or comment form) in your wordpress blog's home/main page,category page or tag page (index.php/archive.php) etc.This makes user view/post comment reply more easily. 
Version: 1.1
*/

/*  Copyright 2011  Farlee  (email : farleeh at gmail.com).
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
$current_ver = '1.1';

if (!get_option('wpaciversioncur')) {
		add_option('wpaciversioncur', $current_ver, 'Set current version of plugin', $autoload);
	} else {
		update_option('wpaciversioncur', $current_ver);
	}	
if (!get_option('wpacicommentorder')) {
		add_option('wpacicommentorder', '0', 'Whether to display ajax comments ascending or descending in order', $autoload);
	}
if (!get_option('wpacicommentnum')) {
		add_option('wpacicommentnum', '10', 'Number of comments Displayed', $autoload);
	}
if (!get_option('wpacigooglejq')) {
		add_option('wpacigooglejq', '0', 'Whether to use Google CDN jQuery', $autoload);
	}	
function wpaciupdate() {
	if ( isset($_POST['wpacicommentorder']) ) {
		$wpaci_commentorder = $_POST['wpacicommentorder'];
		update_option('wpacicommentorder', $wpaci_commentorder);
		$wpaci_commentnum = (int) $_POST['commentnumber'];
		update_option('wpacicommentnum', $wpaci_commentnum);
		$wpaci_googlejq = (int) $_POST['googlejq'];
		update_option('wpacigooglejq', $wpaci_googlejq);
	}
}

function show_hide_comments_link() {
	global $id, $post;
	$comment_count = get_comments_counts();
	
	if (!empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { // if there's a password and the user doesn't know it
		//do nothing
	} else {
		if ($comment_count == '0') {
			echo('<span id="show-ajax-comments-form-'. $id .'"> | ');
				echo('<a href="javascript:void(0);" id="show-ajax-comments-link-'. $id .'" onmouseup="quickCommentform('. $id .');">Comment Shortcut</a>'); 
			echo('</span>');
			echo('<span id="hide-ajax-comments-form-'. $id .'" style="display: none;"> | ');
				echo('<a href="javascript:void(0);" onmouseup="hideCommentform('. $id .');">Collapse</a>'); 
			echo('</span>');
		} elseif ($comment_count == '1') {
			echo('<span id="show-ajax-comments-'. $id .'"> | ');
				echo('<a href="javascript:void(0);" id="show-ajax-comments-link-'. $id .'" onmouseup="showAjaxComments('. $id .');">Show Comments('.$comment_count.')</a>'); 
			echo('</span>');
			echo('<span id="hide-ajax-comments-'. $id .'" style="display: none;"> | ');
				echo('<a href="javascript:void(0);" onmouseup="hideAjaxComments('. $id .');">Hide Comments('.$comment_count.')</a>'); 
			echo('</span>');
		} else if ($comment_count > '1') {
			echo('<span id="show-ajax-comments-'. $id .'"> | ');
				echo('<a href="javascript:void(0);" id="show-ajax-comments-link-'. $id .'" onmouseup="showAjaxComments('. $id .');">Show Comments('.$comment_count.')</a>'); 
			echo('</span>');
			echo('<span id="hide-ajax-comments-'. $id .'" style="display: none;"> | ');
				echo('<a href="javascript:void(0);" onmouseup="hideAjaxComments('. $id .');">Hide Comments('.$comment_count.')</a>'); 
			echo('</span>');
		}
	}
}

function display_ajax_comments() {
	global $id;
	$comment_count = get_comments_counts();
	echo('<div style="clear:both;"></div>');
	if ($comment_count != '0') {
		echo('<div id="ajax-comments-'. $id .'" class="ajax-comments"></div>');	
	}
	echo '<div id="waci-comment-form-'. $id .'" class="waci-comment-form">';
	$commenter = wp_get_current_commenter();
	$fields =  array(
		'author' => '<p class="comment-form-author">' .'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" />' . '<label for="author">' . __( ' Name' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '</p>',
		'email' => '<p class="comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" /><label for="email">' . __( ' Email' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '</p>',
		'url' => '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /><label for="url">' . __( ' Website' ) . '</label></p>',	
	);
	comment_form(array('comment_notes_after' => '', 'comment_notes_before' => '', 'fields' => apply_filters( 'comment_form_default_fields', $fields ),'comment_field' => '<p class="comment-form-comment"><textarea class="waci_comment" name="comment" rows="8" aria-required="true"></textarea><label for="comment">' . _x( '', 'noun' ) . '</label></p>',), $id );
	echo '</div>';
}

function get_comments_counts() {
	global $id, $wpdb, $comment_count_cache;
	if ('' == $comment_count_cache["$id"]) $number = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1'",$id));
	else $number = $comment_count_cache["$id"];
	return $number;
}


function wpaci_header() {
   echo('<script src="' . get_option('siteurl') . '/wp-content/plugins/wordpress-ajax-comments-inline/ajax-comments.js" type="text/javascript"></script>');
   echo '<link type="text/css" rel="stylesheet" href="'.get_option('siteurl').'/wp-content/plugins/wordpress-ajax-comments-inline/wpaci_style.css" />';
}

add_action('wp_head', 'wpaci_header');

function jquery_init() {
    if (0 == get_option('wpacigooglejq')) {
    	if (!is_admin()) {
        	wp_deregister_script( 'jquery' );
        	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js');
        	wp_enqueue_script( 'jquery' );
    	}
    } else {wp_enqueue_script( 'jquery' );}
}    
 
add_action('init', 'jquery_init');

// Add the options page.
add_action ('admin_menu', 'wpacimenu');

function wpacimenu() {
	add_submenu_page('plugins.php', 'Inline Ajax Comments', 'Inline Ajax Comments', 5, 'inline-ajax-comments.php', 'wpaciplugin_menu');
}

function wpaciplugin_menu() {
	if (isset($_POST['Submit'])) : ?>
	<div id="message" class="updated fade">
		<p><?php _e('plugin options have been updated'); ?></p>
	</div>
<?php endif; ?>

<div class="wrap">

<h2><?php _e('Inline Ajax Comments Settings'); ?></h2>
<form name="dofollow" action="" method="post">
  <input type="hidden" name="action" value="<?php wpaciupdate(); ?>" />
  <input type="hidden" name="page_options" value="'dofollow_timeout'" />
  <table width="700px" cellspacing="2" cellpadding="5" class="editform">
		
		<tr valign="top">
		<th scope="row"><?php _e('Comment Order'); ?></th>
		<td>
			<input name="wpacicommentorder" id="inline" type="radio" value="1" <?php checked('1', get_option('wpacicommentorder')); ?> /> 
			<label for="inline"><?php _e('Newest comments first') ?></label><br />
			<input name="wpacicommentorder" id="segmented" type="radio" value="0" <?php checked('0', get_option('wpacicommentorder')); ?> /> 
			<label for="segmented"><?php _e('Oldest comments first - Default') ?></label>
			<p><small>Determines the order that comments are displayed.</small></p>
		</td>
		</tr>

		<tr valign="top">
		<th scope="row"><?php _e('Comment Number'); ?></th>
		<td>
  <input name="commentnumber" id="commentno" type="text" value="<?php echo get_option('wpacicommentnum'); ?>" onkeyup="if(event.keyCode !=37 && event.keyCode != 39) value=value.replace(/\D/g,'');"onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/\D/g,''))"/> 
			<label for="commentno"><?php _e('The number of Comments displayed') ?></label><br />
			<p><small>Determines how many comments would be displayed in the ajax comment page.</small></p>
		</td>
		</tr>

		<tr valign="top">
		<th scope="row"><?php _e('Google Jquery CDN'); ?></th>
		<td>
			<input name="googlejq" id="myjquery" type="radio" value="1" <?php checked('1', get_option('wpacigooglejq')); ?> /> 
			<label for="myjquery"><?php _e('Use WordPress’s Jquery Instead of Google CDN Jquery') ?></label><br />
			<input name="googlejq" id="gojquery" type="radio" value="0" <?php checked('0', get_option('wpacigooglejq')); ?> /> 
			<label for="gojquery"><?php _e('Use Google CDN Jquery Instead of WordPress’s- Default') ?></label>
			<p><small>Determines Whether to use Google CDN jQuery or not.There would be some Advantages using the CDN copy of jQuery from Google API</small></p>
		</td>
		</tr>

		</table>

	<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Now') ?> &raquo;" /></p>
</form>
</div>

<div class="wrap">
	<p style="text-align: center;">You are running inline ajax comments version <?php echo get_option('wpaciversioncur'); ?>
	<p style="text-align: center;">Be sure to check for plugin updates or view plugin details and installation instruction at the <a href="http://farlee.info/archives/wordpress-ajax-comments-inline-plugin.html" title="plugin homepage">wordpress ajax comments inline</a> plugin homepage.</p>
</div>

<?php }  ?>
