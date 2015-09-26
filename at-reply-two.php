<?php
/*
Plugin Name: @Reply Two
Plugin URI: http://halfelf.org/plugins/at-reply-two/
Description: This plugin allows you to add Twitter-like @reply links to comments.
Version: 2.0.1
Author: Mika A. Epstein (Ipstenu)
Author URI: http://halfelf.org
Text Domain: at-reply-two

Forked from @ Reply: http://wordpress.org/plugins/reply-to (Removed the non-threaded code and the images.)

Most of the code is taken from the Custom Smilies plugin by Quang Anh Do which is released under GNU GPL: http://wordpress.org/extend/plugins/custom-smilies/

*/

class AtReplyTwoHELF {

	/**
	 * Construct
	 *
	 * @since 1.0
	 * @access public
	 */
    public function __construct() {
	    if (!is_admin() ) {
		    add_action( 'init', array( &$this, 'init' ) );
		} else {
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
		}
		
		load_plugin_textdomain( 'at-reply-two' );
    }

	/**
	 * Init
	 *
	 * @since 1.0
	 * @access public
	 */
    public function init() {
		if ( get_option( 'thread_comments' ) == '1' ) {
			 add_action('comment_form', array( $this, 'reply_js'));
			 add_filter('comment_reply_link', array( $this,'reply'));
		}
	}

	/**
	 * Admin Init
	 *
	 * @since 2.0
	 * @access public
	 */
    public function admin_init() {
		add_action( 'admin_notices', array( $this, 'admin_notices') );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
		add_filter( 'get_comment_text', array( $this, 'admin_show_parent_comment') );
	}

	/**
	 * Admin Enqueue Scripts
	 *
	 * @since 2.0
	 * @access public
	 */
    public function admin_enqueue_scripts( $hook ) {
		if ( 'edit-comments.php' != $hook && 'post.php' != $hook ) {
			return;
		} else {
			wp_register_style( 'details-shim-css', plugins_url('/details-shim/details-shim.min.css', __FILE__ ) );
			wp_enqueue_style( 'details-shim-css' );
			wp_enqueue_script( 'details-shim-js', plugins_url('/details-shim/details-shim.min.js', __FILE__ ) );
		}
	}

	/**
	 * Admin Notices
	 * 
	 * If thereaded comments are off, alert admin
	 *
	 * @since 1.0
	 * @access public
	 */
	public function admin_notices() {
		if ( get_option( 'thread_comments' ) != '1' ) {

		$html = sprintf(
		    __('@Reply Two requires threaded comments to function properly. <a href="%s">Update Settings Now</a>', 'at-reply-two'),
		    admin_url('options-discussion.php')
		);
			?>
			<div class="error">
				<p><?php echo $html; ?></p>
			</div>
			<?php
		}

	}

	/**
	 * Show Parent Comment
	 * 
	 * Show the parent comment in the Admin Comments listing
	 *
	 * @since 2.0
	 * @access public
	 */
	function admin_show_parent_comment( $content ) {
	    global $comment;

	    if ( !$comment->comment_parent ) {
	        return $content;
	    } else {
	        $parent_comment_id      = get_comment( $comment->comment_parent );
	        $parent_comment_length  = str_word_count( strip_tags( $parent_comment_id->comment_content ) );
	        $parent_comment_message = sprintf( _n('Show Parent Comment (%d word):', 'Show Parent Comment (%d words):', $parent_comment_length, 'at-reply-two'), $parent_comment_length );
	                
	        $parent_comment_content = '<details><summary>'.$parent_comment_message.'</summary><blockquote>' . $parent_comment_id->comment_content . '</blockquote></details>';
	        return $parent_comment_content . $content;
	    }
	}

	/**
	 * Reply JS
	 * 
	 * Javascript to insert the @mention when reply is clicked.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function reply_js() {
	?>
		<script type="text/javascript">
			//<![CDATA[
			function r2_replyTwo(commentID, author) {
				var inReplyTo = '@<a href="' + commentID + '">' + author + '<\/a>: ';
				var myField;
				if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
					myField = document.getElementById('comment');
				} else {
					return false;
				}
				if (document.selection) {
					myField.focus();
					sel = document.selection.createRange();
					sel.text = inReplyTo;
					myField.focus();
				}
				else if (myField.selectionStart || myField.selectionStart == '0') {
					var startPos = myField.selectionStart;
					var endPos = myField.selectionEnd;
					var cursorPos = endPos;
					myField.value = myField.value.substring(0, startPos) + inReplyTo + myField.value.substring(endPos, myField.value.length);
					cursorPos += inReplyTo.length;
					myField.focus();
					myField.selectionStart = cursorPos;
					myField.selectionEnd = cursorPos;
				}
				else {
					myField.value += inReplyTo;
					myField.focus();
				}
			}
			//]]>
		</script>
	<?php
	}

	/**
	 * Reply
	 * 
	 * Creating the proper link using the JS.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function reply($reply_link) {
		 $comment_ID = '#comment-' . get_comment_ID();
		 $comment_author = esc_html(get_comment_author());
		 $r2_reply_link = 'onclick=\'return r2_replyTwo("' . $comment_ID . '", "' . $comment_author . '"),';
		 return str_replace("onclick='return", "$r2_reply_link", $reply_link);
	}
}

new AtReplyTwoHELF();