<?php 
/*
* 
* @link              https://mackraicevic.com
* @since             1.0.0
* @package           NFL_Plugin
*
* @wordpress-plugin
* Plugin Name:       NFL Teams
* Plugin URI:        https://mackraicevic.com
* Description:       NFL Teams plugins is created from the public API and diplays teams list on the front end using shortcode. Visit the plugin Settigs page to get a Shortcode
* Version:           1.0.0
* Author:            Mack Raicevic
* Author URI:        https://mackraicevic.com
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       nfl-teams
* Domain Path:       /languages
*/

// Prevent Direct Access
defined( 'ABSPATH' ) or die( 'Nothing interesting here.' );

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}


use Inc\Base\Activate;
use Inc\Base\Deactivate;
use Inc\Admin\Admin_Pages;

define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PLUGIN_NAME', plugin_basename( __FILE__ ) );



if( !class_exists( 'NFL_Plugin' ) ) {

  class NFL_Plugin {

  
    function register(){
      // add admin menu
      // attach scripts
      add_action( 'admin_menu', array( $this, 'add_admin_pages') );
  
      // setup setting slinks for our plugin
      // escape with double quotes and make variable read as string
      add_filter( "plugin_action_links_" . PLUGIN_NAME, array( $this, 'settings_link' ) );


      //add shortcode for diplaying NFL teams
      add_shortcode('nfl-teams-display', array( $this, 'shortcode_init') );

    }

    function enqueue_style_front_end(){
      add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_css_front_end' ) );
    }
  
    function settings_link( $links ){
      // add custom settings link
      $settings_link = '<a href="admin.php?page=nfl_teams_plugin">Settings</a>';
      array_push( $links, $settings_link );
      return $links;
    }
  
    function add_admin_pages(){
      // initialize admin menu
      $admin_menu = add_menu_page(
        'NFL Teams Plugin Page', 
        'NFL Teams',
        'manage_options',
        'nfl_teams_plugin', 
        array( $this, 'admin_index' ),
        'dashicons-admin-users'
      );

      add_action( 'admin_print_styles-' . $admin_menu, array( $this, 'enqueue_css'));
      add_action( 'admin_print_scripts-' . $admin_menu, array( $this, 'enqueue_script'));

    }
  
    function admin_index(){
      // require template
      require_once PLUGIN_URL . '/templates/admin_page.php';
    }

   
    function shortcode_init(){
      ob_start();
      include PLUGIN_URL . 'templates/shortcode.php';
      return ob_get_clean();
    }
  
    function activate(){
      Activate::activate();
    }
  
    
    function enqueue_css_front_end(){
      // front end only
      wp_enqueue_style( 'nflpluginstyle', PLUGIN_URL . 'assets/css/nflstyle.css' );
    }

    function enqueue_css(){
      // enqueue style css
      wp_enqueue_style( 'bootstrapcss', PLUGIN_URL . 'assets/css/bootstrap.min.css' );
      wp_enqueue_style( 'nflpluginstyle', PLUGIN_URL . 'assets/css/nflstyle.css' );
    }
    function enqueue_script(){
      // enqueue js scripts
      wp_enqueue_script( 'bootstrapjs', PLUGIN_URL . 'assets/js/bootstrap.min.js' );
      wp_enqueue_script( 'nflpluginscript', PLUGIN_URL . 'assets/js/nflmain.js' );
    }


  
  }


  // Initialize the NFL_Plugin class
  $nfl_plugin = new NFL_Plugin;
  $nfl_plugin->register();
  $nfl_plugin->enqueue_style_front_end();

  // activation
  register_activation_hook( __FILE__, array( $nfl_plugin, 'activate' ) );



  // deactivation
  register_deactivation_hook( __FILE__, array( 'Deactivate', 'deactivate' ) );

}




