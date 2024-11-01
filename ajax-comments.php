<?php
	require_once('../../../wp-config.php');
	global $comment, $comments, $post, $wpdb, $authordata;
	$id = (int) $_POST['id'];
	$post = &get_post($id);
	$comlimitnum = (int) get_option('wpacicommentnum');
	$comment_total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1'", $id));
	if (0 == get_option('wpacicommentorder')) {
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' ORDER BY comment_date ASC LIMIT %d",$id,$comlimitnum));
		$comment_num = 1;
	} else {
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' ORDER BY comment_date DESC LIMIT %d",$id,$comlimitnum));
		$comment_num = $comment_total;
	}
	
	$authordata = get_userdata($post->post_author);
?>

<div id="comText<?php echo $id; ?>"> 

	<div class="ajax-comments-wrapper">
		
			<?php foreach ($comments as $comment) : ?>
				<?php $authcomment=''; if ($comment->comment_author_email == $authordata->user_email) { $authcomment=1; } ?>				
				<div class="ajax-comment<?php if ($authcomment) {echo '-alt';} ?>">
					<div class="ajax-comment-header<?php if ($authcomment) {echo '-alt';} ?>"><a id="comment-<?php comment_ID() ?>"></a>
					<span class="ajax-comment-info">
					   		<?php if ('' == $comment->comment_type) { ?>
								<?php comment_date('Y-n-j') ?> | 
								<?php comment_time('G:i:s') ?> | 
								#<?php echo $comment_num; ?>
							<?php } elseif ('trackback' == $comment->comment_type) { ?>
								<?php _e('Trackback'); ?>
							<?php } elseif ('pingback' == $comment->comment_type) { ?>
								<?php _e('Pingback'); ?>
							<?php } ?>
					</span>
					<span class="ajax-comment-author"><?php comment_author_link() ?></span>
					</div>
					<div class="ajax-comment-text">
						<?php comment_text() ?>
					</div>
				</div>
				
				<?php if (0 == get_option('wpacicommentorder')) {
							$comment_num++;
						} else {
							$comment_num--;
						} ?>
				
			<?php endforeach; /* end for each comment */ ?>
	
	<!-- <span class="ajax-options"><a href="<?php // comments_link(); ?>">Leave a Reply</a></span> -->
<span id="show-ajax-comments-form-<?php echo $id; ?>" class="ajax-options"><a href="javascript:void(0);" id="show-ajax-comments-link-<?php echo $id; ?>" onmouseup="quickCommentform(<?php echo $id; ?>);">Leave a Reply</a></span>
<span id="hide-ajax-comments-form-<?php echo $id; ?>" class="ajax-options-hide"><a href="javascript:void(0);" onmouseup="hideCommentform(<?php echo $id; ?>);">Collapse</a></span>	
		
		<span class="more"><?php if ($comment_total > $comlimitnum){$com_more = $comment_total - $comlimitnum; echo "There are ".$com_more." more comments. <a href='";?><?php comments_link(); echo "'>View</a>"; } ?></span>
	</div>
</div>
