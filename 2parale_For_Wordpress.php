<?php
/*
 Plugin Name: 2Parale for WordPress
 Author: Andrei Baragan
 Author URI: http://andlei.blogspot.com/
 Plugin URI: http://andlei.blogspot.com/2009/04/2parale-pentru-wordpress.html
 Description: Allows WordPress users to quickly and easily insert 2Parale product links into posts and pages.
 Version: 1.0
 */

require_once 'lib/2parale.php';
require_once 'lib/utils.php';
define('DOUAPARALE_PER_PAGE', 9);

// Ensures that no one has already named a class the same as the one to be loaded by this plugin
if( !class_exists( '2Parale_For_WordPress' ) ) 
{
	// Unique class name to avoid naming collisions.
	class DouaParale_For_WordPress 
	{
		var $options;
		var $plugin_folder;
		var $version = '1.0';
		var $dp;
		
		/**
		 * PHP4 backwards compatibility;
		 */
		function DouaParale_For_WordPress() 
		{
			$this->__construct();
		}

		/**
		 * Default constructor registers filter, action, and activation deactivation hooks.  It also
		 * loads the plugins options.
		 * 
		 * @return 2Parale_For_WordPress a new instance of the 2Parale_For_WordPress class.
		 */
		function __construct() 
		{
			// Activation/Deactivation
			register_activation_hook(__FILE__, array(&$this, 'on_activate'));
			register_deactivation_hook(__FILE__, array(&$this, 'on_deactivate'));
				
			// Add Actions
			add_action('admin_menu', array(&$this, 'on_admin_menu'));
			add_action('init', array(&$this, 'on_init'));
			add_action('wp_ajax_2Parale_for_wordpress', array(&$this, 'on_wp_ajax_2Parale_for_wordpress'));
				
			// Other Stuff
			$this->load_options();
			$this->plugin_folder = path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)));
			$this->api_init();
		}

		/**
		 * Hook for activate action.
		 *
		 * Adds the plugin's option to the database.
		 */
		function on_activate() 
		{      
			$this->load_options();
			$this->save_options();
		}
		
		/**
		 * Hook for deactivate action.
		 *
		 * Removes the plugin's option from the database.
		 */
		function on_deactivate() 
		{
			$this->delete_options();
		}

		/**
		 * Hook for the admin_menu action.
		 *
		 * Enqueues proper javascript files for operation of the plugin.  Also adds the appropriate options page
		 * that lets the user manage their 2Parale user and password.
		 */
		function on_admin_menu() 
		{
			// If we're on the write post or write page interface, add the 2Parale For Wordpress javascript
			if($this->is_write_page()) 
			{
				$css_file = path_join($this->plugin_folder, 'css/2Parale.css');
				$js_file = path_join($this->plugin_folder, 'js/2Parale.js');

        wp_enqueue_script('jquery-132', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', array(), $this->version);
				wp_enqueue_script('2Parale-for-wordpress', $js_file, array('jquery'), $this->version);
				wp_enqueue_style('2Parale-for-wordpress', $css_file, array(), $this->version);
			}
				
			// Add the plugin option page
			add_options_page('2Parale', '2Parale', 8, basename( __FILE__ ), array(&$this, 'options_page'));
				
			// Add the meta box to the post and page interfaces.  
			add_meta_box(__('2Parale', '2Parale-for-wordpress'), __('2Parale' , '2Parale-for-wordpress'), array(&$this, 'meta_box_output'), 'post', 'side', 'high');
			add_meta_box(__('2Parale' , '2Parale-for-wordpress'), __('2Parale' , '2Parale-for-wordpress'), array(&$this, 'meta_box_output'), 'page', 'side', 'high' );
		}

		/**
		 * Loads the plugin's text domain.
		 *
		 */
		function on_init() 
		{
		}
		
		/**
		 * Hook for wp_2parale_for_wordpress action.
		 *
		 * Request results from 2Parale web services and prints them to output.
		 */
		function on_wp_ajax_2Parale_for_wordpress() 
		{
		  if ($this->dp) 
		  {
		    if (!isset($_POST['campaign']) || !$_POST['campaign']) 
		    {
		      echo '<p>' . __('Please select a campaign.') . '</p>';
		      exit();
		    }
		    
		    $page = $_POST['page'] ? $_POST['page'] : 1;
 		    
		    if ($_POST['searchtype'] == 'banner') 
		    {
		      $products = $this->dp->banners_search($_POST['campaign'], $_POST['search'], 1, $per_page = 999);
  		    $products = $this->to_array($products);
  		    $total = count($products);
  		    $products = array_slice($products, ($page - 1) * DOUAPARALE_PER_PAGE, DOUAPARALE_PER_PAGE);

  		    require_once(path_join(dirname( __FILE__ ), 'pages/product_list_banner.php'));
		    }
		    else
		    {
		      $products = $this->dp->txtlinks_search($_POST['campaign'], $_POST['search'], 1, $per_page = 999);
  		    $products = $this->to_array($products);
  		    $total = count($products);
  		    $products = array_slice($products, ($page - 1) * DOUAPARALE_PER_PAGE, DOUAPARALE_PER_PAGE);

  		    require_once(path_join(dirname( __FILE__ ), 'pages/product_list_text.php'));
		    }
		  }
			else 
			{
				$response = '<p>' . __( 'Cannot connect to 2Parale API. Make sure you have configured your 2Parale username & password.' , '2Parale-for-wordpress') . '</p>';	
				echo $response;
			}

			exit();
		}

		/**
		 * Deletes the plugin's options from the database.
		 *
		 */
		function delete_options() 
		{ 
			return delete_option( '2Parale for WordPress Options' );
		}
		
		/**
		 * Retrieves the plugin's options from the database.
		 *
		 */
		function load_options() 
		{
			if(false === ($options = get_option('2Parale for WordPress Options'))) 
			{
				$this->options = $this->defaults;
			} 
			else 
			{
				$this->options = $options;
			}
			return $this->options;
		}

		/**
		 * Returns a boolean value indicating whether the currently requested page is a
		 * part of the write page or write post interface.
		 *
		 * @return boolean true if the currently requested page is a write interface for posts or pages.
		 */
		function is_write_page() 
		{
			$is_new_post = (false != strpos($_SERVER[ 'REQUEST_URI' ], 'post-new.php'));
			$is_new_page = (false != strpos($_SERVER[ 'REQUEST_URI' ], 'page-new.php'));
			$is_post = (false != strpos($_SERVER[ 'REQUEST_URI' ], 'post.php'));
			$is_page = (false != strpos( $_SERVER[ 'REQUEST_URI' ], 'page.php'));
			
			return $is_new_post || $is_new_page || $is_post || $is_page;
		}

		/**
		 * Displays the output for the plugin's meta box.
		 * 
		 */
		function meta_box_output() 
		{
		  // Check if the we have username and password
		  if (!$this->options['2Parale_user'] || !$this->options['2Parale_password']) 
		  {
		    echo '<p>'.__('<b>Please fill in your 2Parale username and password in the settings page.</b> <br/>(Under Settings / 2Parale)').'</p>';
		    return;
		  }
		  
		  // Check if username and password are ok by trying to get active camp
		  if (!check_2parale_connection($this->options['2Parale_user'], $this->options['2Parale_password'])) 
		  {
		    echo '<p>'.__('Cannot communicate with 2Parale. <br/><b>Do you have the right username and password ?</b>').'</p>';
		    return;
	    }
		  
			require_once (path_join( dirname( __FILE__ ), 'pages/meta_box.php'));
		}

		/**
		 * Processes submissions and displays the output for the plugin's option page.
		 * 
		 */
		function options_page() 
		{
		  $parale_errors = array();
		  $parale_message = '';
		  if ($_POST) 
		  {
		    // Processing
  			if($_POST['2Parale_user'] && $_POST['2Parale_password'] && check_admin_referer('2Parale-for-wordpress-save_options')) 
  			{
          if (!check_2parale_connection($_POST['2Parale_user'], $_POST['2Parale_password']))
          {
            $parale_errors[] = '<p>' . __( 'Cannot login with the username and password.' , '2parale-for-wordpress') . '</p>';
          }
          else
          {
            $parale_user = $_POST['2Parale_user'];
    				$parale_password = $_POST['2Parale_password'];

    				$this->options[ '2Parale_user' ] = $parale_user;
    				$this->options[ '2Parale_password' ] = $parale_password;
    				$this->save_options();				
          }
  			}
  			else
  			{
  			  $parale_errors[] = '<p>' . __( 'Please enter your 2Parale username and password.' , '2parale-for-wordpress') . '</p>';
  			}

  			if(empty($parale_errors)) 
  			{
  				$parale_message = '<p>' . __( 'Your options have been saved.' , '2Parale-for-wordpress') . '</p>';
  			} 
  			else 
  			{
  				$parale_message = '<ul>';
  				foreach($parale_errors as $error) 
  				{
  					$parale_message .= "<li>$error</li>";
  				}
  				$parale_message .= '</ul>';
  			}
		  }

			// Display
			require_once (path_join(dirname( __FILE__ ), 'pages/options.php'));
		}

		/**
		 * Provides an easy mechanism to save the options value to the WordPress database
		 * for persistence.
		 *
		 * @return boolean true if the save was successful.
		 */
		function save_options( ) 
		{
			return update_option('2Parale for WordPress Options', $this->options);
		}

		/**
		 * Processes the key appropriately
		 *
		 * @param string $key the key before processing.
		 * @return string the key after processing.
		 */
		function utility( $key ) 
		{
			return base64_decode(base64_decode(base64_decode(base64_decode(base64_decode(base64_decode( $key ))))));
		}

		/**
		 * Get the active campaigns
		 *
		 * @return campaign array
		 */
		function get_active_campaigns()
		{
		  $subscribed_campaigns = $this->dp->campaigns_listforaffiliate();
      if ($subscribed_campaigns) 
      {
        $subscribed_campaigns = $this->to_array($subscribed_campaigns);
      }
      
      return $subscribed_campaigns;
		}
		
		/**
		 * Init DouaParale api
		 *
		 * @return DouaParale API object
		 */
		function api_init()
		{
		  $this->dp = new DouaParale($this->options['2Parale_user'], $this->options['2Parale_password']);
		  return $this->dp;
		}
		
		/**
		 * Transform a single item into an array. If an array is given then return that array.
		 *
		 * @return array
		 */
		
		function to_array($input)
		{
		  if ($input) 
		  {
		    if (!is_array($input))
        {
          $input = array($input);
        }
		  }
      return $input;
		}
	}
}

if( class_exists( 'DouaParale_For_WordPress' ) ) {
	$parale = new DouaParale_For_WordPress();
}

?>