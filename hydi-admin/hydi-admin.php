<?php 
/**
 *@package Hydi_Admin
 */
    /**
    * Plugin Name: Hydi Admin
    * Plugin URI: http://hydi.voqux.com
    * Description: For administration of HYDI and its database
    * Author: Eugene Ng
    * Version: 1.0
    * Author URI: http://hydi.voqux.com
    **/

    /*==============================================/
    Contents
    HA-00. Main
    HA-01. init()
        HA-01a. hydi_admin_theme_style()
        HA-01b. adminMenu_init()
    ===============================================*/

    /**
    * HA-00. Main
    */
    hydi_admin_init();

    /**
    * HA-01. init
    *   a. Call CSS
    *   b. Initialize admin menu
    */
    function hydi_admin_init(){
        //HA-01a. Call CSS
        hydi_admin_theme_style();

        //HA-01b. Initialize admin menu
        adminMenu_init();

    }

    /**
    * HA-01a. Enqueue custom CSS
    */
    function hydi_admin_theme_style() {
	    wp_enqueue_style('hydi-admin-theme', plugins_url('hydi-admin.css', __FILE__));

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-timepicker',  
            '//cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.8.1/jquery.timepicker.min.js',
            array('jquery'));            
	}

    /**
    * HA-01b. adminMenu_init
    * Add menu items into the wordpress admin menu
    * - HYDI
    */
    function adminMenu_init(){
        //HA-01b. Hook for adding admin menus
        add_action('admin_menu', 'mt_add_pages');

        // action function for above hook
        function mt_add_pages() {
            // Add a new top-level menu:
            add_menu_page(__('Hydi Admin',''), __('HYDI',''), 'manage_options', 'hydi-admin', 'hydi_admin_page' );

            // Add a submenu to the custom top-level menu:
            add_submenu_page('hydi-admin', __('Add an Activity',''), __('Add Activity',''), 'manage_options', 'add', 'hydi_add_activity_page');

            // Add a second submenu to the custom top-level menu:
            add_submenu_page('hydi-admin', __('Update an Activity',''), __('Update Activity',''), 'manage_options', 'update', 'hydi_update_activity_page');
            add_submenu_page('hydi-admin', __('Pending Requests',''), __('Pending Activities',''), 'manage_options', 'pending', 'hydi_pending_activities_page');

            // Add a new submenu under Settings:
            //add_options_page(__('Test Settings','menu-test'), __('Test Settings','menu-test'), 'manage_options', 'testsettings', 'mt_settings_page');

            // Add a new submenu under Tools:
            //add_management_page( __('Test Tools','menu-test'), __('Test Tools','menu-test'), 'manage_options', 'testtools', 'mt_tools_page');
        }

        // hydi_admin_page() displays the main hydi admin page
        function hydi_admin_page() {
            echo "<h2>" . __( 'Hydi Admin', '' ) . "</h2>";
            include 'main-page.php';
        }

        // hydi_add_activity_page() displays the page content for the first submenu
        // of the custom Test Toplevel menu
        function hydi_add_activity_page() {
            echo "<h2>" . __( 'Add an Activity', '' ) . "</h2>";
            include 'new-activity-form.php';
        }

         // hydi_update_activity_page() displays the page content for the second submenu
        // of the custom Test Toplevel menu
        function hydi_update_activity_page() {
            echo "<h2>" . __( 'Update an Activity', '' ) . "</h2>";
        }

        // hydi_pending_activities_page() displays the page content for the second submenu
        // of the custom Test Toplevel menu
        function hydi_pending_activities_page() {
            echo "<h2>" . __( 'Pending Activities', '' ) . "</h2>";
            include 'pending-activities.php';
        }

        /*
        // mt_settings_page() displays the page content for the Test settings submenu
        function mt_settings_page() {
            echo "<h2>" . __( 'Test Settings', 'menu-test' ) . "</h2>";
        }

        // mt_tools_page() displays the page content for the Test Tools submenu
        function mt_tools_page() {
            echo "<h2>" . __( 'Test Tools', 'menu-test' ) . "</h2>";
        }

       
        */
    }
?>