<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to presscore_comment() which is
 * located in the inc/template-tags.php file.
 *
 * @package presscore
 * @since presscore 0.1
 */
?>

<?php
	// File Security Check
	if ( ! defined( 'ABSPATH' ) ) { exit; }

	/*
	 * If the current post is protected by a password and
	 * the visitor has not yet entered the password we will
	 * return early without loading the comments.
	 */
	if ( post_password_required() || ( !comments_open() && 0 == get_comments_number() ) ) {
		return;
	}
?>

	<div id="comments" class="comments-area">
	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', LANGUAGE_ZONE ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav role="navigation" id="comment-nav-above" class="site-navigation comment-navigation">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', LANGUAGE_ZONE ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', LANGUAGE_ZONE ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', LANGUAGE_ZONE ) ); ?></div>
		</nav><!-- #comment-nav-before .site-navigation .comment-navigation -->
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use presscore_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define presscore_comment() and that will be used instead.
				 * See presscore_comment() in inc/template-tags.php for more.
				 */
				wp_list_comments( array( 'callback' => 'presscore_comment' ) );
			?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', LANGUAGE_ZONE ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', LANGUAGE_ZONE ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', LANGUAGE_ZONE ) ); ?></div>
		</nav><!-- #comment-nav-below .site-navigation .comment-navigation -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', LANGUAGE_ZONE ); ?></p>
	<?php endif; ?>
	<?php
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$required_text = sprintf( ' ' . __('Required fields are marked %s', LANGUAGE_ZONE), '<span class="required">*</span>' );

	$comment_form_args = array(
		'fields'	=> apply_filters( 'comment_form_default_fields', array(

			'author' => '<div class="form-fields"><span class="comment-form-author">' . '<label class="assistive-text" for="author">' . __( 'Name &#42;', LANGUAGE_ZONE ) . '</label><input id="author" name="author" type="text" placeholder="' . __( 'Name&#42;', LANGUAGE_ZONE ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></span>',

			'email' => '<span class="comment-form-email"><label class="assistive-text" for="email">' . __( 'Email &#42;', LANGUAGE_ZONE ) . '</label><input id="email" name="email" type="text" placeholder="' . __( 'Email&#42;', LANGUAGE_ZONE ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></span>',

			'url' => '<span class="comment-form-url"><label class="assistive-text" for="url">' . __( 'Website', LANGUAGE_ZONE ) . '</label><input id="url" name="url" type="text" placeholder="' . __( 'Website', LANGUAGE_ZONE ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></span></div>'

			)
		),

		'comment_field'	=> '<p class="comment-form-comment"><label class="assistive-text" for="comment">' . __( 'Comment', LANGUAGE_ZONE ) . '</label><textarea id="comment" placeholder="' . __( 'Comment', LANGUAGE_ZONE ) . '" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',

		'comment_notes_after' => '<p class="form-allowed-tags text-small wf-mobile-hidden">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', LANGUAGE_ZONE ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',

		'must_log_in' => '<p class="must-log-in text-small">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', LANGUAGE_ZONE ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',

		'logged_in_as' => '<p class="logged-in-as text-small">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', LANGUAGE_ZONE ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',

		'comment_notes_before' => '<p class="comment-notes text-small">' . __( 'Your email address will not be published.', LANGUAGE_ZONE ) . ( $req ? $required_text : '' ) . '</p>',

	);
	?>
	<?php comment_form( $comment_form_args ); ?>

	</div><!-- #comments .comments-area -->
