=== Wordpress Ajax Comments Inline ===
Contributors: farlee
Tags: ajax comments, comment, inline, comment form
Requires at least: 2.0.2
Tested up to: 3.1
Stable tag: 1.1

Display inline ajax comments (comment form) of one post in home page,category,tag page etc.

== Description ==

This plugin displays ajax comments inline for each post using jQuery,click to show or hide the ajax comments(or comment form) in your wordpress blog's home/main page,category page or tag page (index.php/archive.php) etc.This makes user view/post comment reply more easily.The main features are:

**Show/Hide ajax comments inline**

Click *Show comments* link of one post in wp blog's home page(or category,tag archive page etc.),the comments of this post would be displayed inline.This plugin uses jQuery ajax to get the comments from server,so only the comments area would be refresh, save bandwidth and make your wordpress blog be loaded much faster.After that,you have a choice to collapse the ajax comments by clicking the *Hide Comment* link.

The inline comment form would be displayed if there is no comment yet.Also the comment form could be collapsed.

**Availabe to set Comment order and Comment number**

You can choose how many comments to be displayed in the inline ajax comments field, set the new or old comments displayed first.

**Option to use Google jQuery CDN or not**

This plugin requires jQuery, the most popular js framework. It loads Google jQuery CDN (content delivery network) by default.There would be some advantages to server Google jQuery CDN. However for some reason,you could choose to load jQuery from your wordpress blog's own libraries.

See the [Wordpress ajax comments inline](http://farlee.info/archives/wordpress-ajax-comments-inline-plugin.html) homepage for more datails.

== Installation ==

1. Upload the whole WordPress Ajax Comments Inline plugin directory to your wordpress blog `/wp-content/plugins/` directory.
1. Activate the plugin through the "Plugins" menu in WordPress Dashboard.
1. Place `<?php show_hide_comments_link(); ?>` in your theme's template file where you expect the "Show Comments" / "Hide Comments" link to be displayed.
1. Place  `<?php display_ajax_comments(); ?>` in your theme's template file where you want the ajax comment field (or comment form) to be displayed.
1. You can place the short code above in your theme's index.php (or home.php,front-page.php: display inline ajax comments in wordpress blog's home page), or archive.php(category.php,tag.php,date.php etc.,display inline ajax comments in the category or tag page etc.).Take a look at the example bellow for details.
1. If the style doesn't suit for your theme, you can modify the css file *wordpress-ajax-comments-inline/wpaci_style.css* in the plugin directory to custimize how it looks.
1. Go to the admin page: *Dashboard-Plugins-Inline Ajax Comments* to set the settings if you don't want the default one.

**Example**:modify index.php:

>    `<?php while ( have_posts() ) : the_post(); ?>`
>        `.....`
>        `<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>`
>           `<div class="entry-meta"></div><!-- .entry-meta -->`
>           `<div class="entry-content"></div><!-- .entry-content -->`
>           `<div class="entry-utility">`
>              `<span><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '1 Comment', 'twentyten' ), __( '% Comments', 'twentyten' ) ); ?></span>`
>              `<?php show_hide_comments_link(); ?>`
>           `</div><!-- .entry-utility -->`
>           `<?php display_ajax_comments(); ?>`
>        `</div><!-- #post-## -->`
>    `<?php endwhile; // End the loop. Whew. ?>`


== Frequently Asked Questions ==

= Could I replace the default comment link =

Of course,you could replace `<span><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '1 Comment', 'twentyten' ), __( '% Comments', 'twentyten' ) ); ?></span>` with inline ajax comment link fuction `<?php show_hide_comments_link(); ?>`.

= Where to place the code =

Here is the best practice: Search the default wordpress comment link display funtion: `comments_popup_link`, place `<?php show_hide_comments_link(); ?>` after it outside the html tag  `<span>...</span>`. Then search `</div>` from this position below, place `<?php display_ajax_comments(); ?>`just after the first found `</div>` tag.

Note: The short code must be placed in the loop, as in this example, put it between `<?php while ( have_posts() ) : the_post(); ?>` and `<?php endwhile; // End the loop. Whew. ?>`.

== Screenshots ==

1. Hide displayed ajax comments
2. Show ajax comments/collapse comment form

== Changelog ==

= 1.1 =
* Fix bug: Fix the bug when the code is placed at the none main page(category page etc.),the error `Failed to get comments:error` occur.
* New feature added: Add comment form in the bottom of ajax comments field if the posts already have comment.So the comment form could be clicked to display whether the post has comments or not.

= 1.0 =
* The first version.
* Report bug if you find in the plugin page:[Wordpress ajax comments inline](http://farlee.info/archives/wordpress-ajax-comments-inline-plugin.html).

