<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/////////////////////////////
// Top bar contacts list //
/////////////////////////////

if ( of_get_option('top_bar-contact_show', 1) ) : ?>
	<div class="mini-contacts wf-float-left">
		<ul>
			<?php presscore_top_bar_contacts_list(); ?>
		</ul>
	</div>
<?php endif; // mini contacts

////////////////////
// Top bar menu //
////////////////////

presscore_nav_menu_list('top', 'left');

////////////////////
// Top Bar text //
////////////////////

$top_text = of_get_option('top_bar-text', '');
if ( $top_text ) :
?>

	<div class="wf-float-left">
		<?php echo wpautop($top_text); ?>
	</div>

<?php endif; // top text
