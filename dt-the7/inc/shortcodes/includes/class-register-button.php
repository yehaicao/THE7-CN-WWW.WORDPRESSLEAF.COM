<?php
/**
 * Class which register tinymce buttons.
 *
 */

class DT_ADD_MCE_BUTTON {
    static $plugins = array();
    static $buttons = array();
    static $action_added = false;
    
    function __construct( $plugin_name, $plugin_dir, $buttons = array(), $level = 3 ) {
        
        $level = absint( $level );

        // store plugin name and dir
        self::$plugins[ $plugin_dir ] = $plugin_name;

        // create level subarray
        if ( !isset( self::$buttons[ $level ] ) ) {
            self::$buttons[ $level ] = array();
        }

        // if set - store custom buttons
        // it must be array
        self::$buttons[ $level ][ $plugin_name ] = empty( $buttons ) ? array( $plugin_name ) : (array) $buttons;

        // init process for button control
        if ( !self::$action_added ) {
            add_action( 'init', array ( __CLASS__, 'add_buttons' ) );
            self::$action_added = true;
        }
    }
    
    public static function add_buttons() {
        // Don't bother doing this stuff if the current user lacks permissions
        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
            return;
        }
        
        // Add only in Rich Editor mode
        if ( get_user_option('rich_editing') == 'true') {
            
            // add the button for wp2.5 in a new way
            add_filter( 'mce_external_plugins', array( __CLASS__, 'add_plugins' ) );
            add_filter( 'mce_buttons', array( __CLASS__, 'register_buttons' ) );
            add_filter( 'mce_buttons_2', array( __CLASS__, 'register_buttons' ) );
            add_filter( 'mce_buttons_3', array( __CLASS__, 'register_buttons' ) );
            add_filter( 'mce_buttons_4', array( __CLASS__, 'register_buttons' ) );
        }
    }
    
    // used to insert button in wordpress 2.5x editor
    public static function register_buttons( $buttons ) {

        switch( current_filter() ) {
            case 'mce_buttons' : $cur_lvl = 1; break;
            case 'mce_buttons_2' : $cur_lvl = 2; break;
            case 'mce_buttons_4' : $cur_lvl = 4; break;
            default : $cur_lvl = 3;
        }

        // if there is no buttons for current level - return
        if ( isset( self::$buttons[ $cur_lvl ] ) ) {
            $cur_lvl_btns = self::$buttons[ $cur_lvl ];
        } else {
            return $buttons;
        }

        // add buttons
        foreach ( $cur_lvl_btns as $plugin_name=>$plugin_buttons ) {
            $buttons = array_merge( $buttons, $plugin_buttons );
        }

        return $buttons;
    }
    
    // Load the TinyMCE plugin : editor_plugin.js (wp2.5)
    public static function add_plugins( $plugins_array ) {
        foreach ( self::$plugins as $plugin_dir=>$plugin_name ) { 
            $plugins_array[ $plugin_name ] =  trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_URI ) . $plugin_dir . '/plugin.js';	
        }
        return $plugins_array;
    }
}
