<?php
/*
Plugin Name: @Reply Two
Plugin URI: http://wordpress.org/extend/plugins/reply-to/
Description: This plugin allows you to add Twitter-like @reply links to comments.
Version: 1.0
Author: Mika A. Epstein (Ipstenu)
Author URI: http://halfelf.org

Forked from @ Reply: http://wordpress.org/plugins/reply-to (Removed the non-threaded code, and the images.)

Most of the code is taken from the Custom Smilies plugin by Quang Anh Do which is released under GNU GPL: http://wordpress.org/extend/plugins/custom-smilies/

*/

class AtReplyTwoHELF {
    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
    }

    public function init() {
		if (!is_admin()) {
			 add_action('comment_form', array( $this, 'r2_reply_js'));
			 add_filter('comment_reply_link', array( $this,'r2_reply'));
		}
	}

	public function r2_reply_js() {
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

	public function r2_reply($reply_link) {
		 $comment_ID = '#comment-' . get_comment_ID();
		 $comment_author = esc_html(get_comment_author());
		 $r2_reply_link = 'onclick=\'return r2_replyTwo("' . $comment_ID . '", "' . $comment_author . '"),';
		 return str_replace("onclick='return", "$r2_reply_link", $reply_link);
	}
}

new AtReplyTwoHELF();
