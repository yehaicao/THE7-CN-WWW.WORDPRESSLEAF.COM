<?php
/**
 * Private Content mod.
 *
 */

function presscore_private_content_in_theme_menu( $item, $depth, $args, $id, $item_id ) {
?>

    <p class="field-custom description description-wide pg_menu_restr_wrap">
        <label for="edit-menu-item-custom-<?php echo $item_id; ?>"><?php _e('Which PrivateContent user categories can see the page?', LANGUAGE_ZONE) ?></label>
				
		<?php 

        $menu_hide = $item->pg_hide_item;
        if(!is_array($menu_hide)) {$menu_hide = array('');}
        ?>
        
        <select name="menu-item-pg-hide-<?php echo $item_id; ?>[]" multiple="multiple" class="chzn-select pg_menu_select" data-placeholder="<?php _e('Select categories', LANGUAGE_ZONE) ?> .." tabindex="2">
          <option value="all" class="pg_all_field" <?php if(isset($menu_hide[0]) && $menu_hide[0]=='all') echo 'selected="selected"'; ?>>
		  	<?php _e('All', LANGUAGE_ZONE) ?>
          </option>
          <option value="unlogged" class="pg_unl_field" <?php if(isset($menu_hide[0]) && $menu_hide[0]=='unlogged') echo 'selected="selected"'; ?>>
		  	<?php _e('Unlogged Users', LANGUAGE_ZONE) ?>
          </option>
          
          <?php
          $user_categories = get_terms('pg_user_categories', 'orderby=name&hide_empty=0');
          foreach ($user_categories as $ucat) {
              (isset($menu_hide[0]) && in_array($ucat->term_id, $menu_hide)) ? $selected = 'selected="selected"' : $selected = '';
              
              echo '<option value="'.$ucat->term_id.'" '.$selected.'>'.$ucat->name.'</option>';  
          }
          ?>
        </select>   
    </p>
    
    <script src="<?php echo PG_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" charset="utf8">
    jQuery(document).ready(function($) {
        if(typeof(pg_menu_js_init) == 'undefined') {
			pg_menu_js_init = 1;
		
			jQuery('body').delegate('.pg_menu_select', 'change', function() {
				var pg_sel = jQuery(this).val();
				if(!pg_sel) {pg_sel = [];}
				
				// if ALL is selected, discard the rest
				if(jQuery.inArray("all", pg_sel) >= 0) {
					jQuery(this).children('option').prop('selected', false);
					jQuery(this).children('.pg_all_field').prop('selected', true);
					
					jQuery(this).trigger("liszt:updated");
				}
				
				// if UNLOGGED is selected, discard the rest
				else if(jQuery.inArray("unlogged", pg_sel) >= 0) {
					jQuery(this).children('option').prop('selected', false);
					jQuery(this).children('.pg_unl_field').prop('selected', true);
					
					jQuery(this).trigger("liszt:updated");
				}		
			});
			
			// chosen
			jQuery('.chzn-select').each(function() {
				jQuery(".chzn-select").chosen(); 
				jQuery(".chzn-select-deselect").chosen({allow_single_deselect:true});
			});	
		}
    });
    </script>

<?php
}
add_action( 'dt_edit_menu_walker_print_item_settings', 'presscore_private_content_in_theme_menu', 10, 5 );