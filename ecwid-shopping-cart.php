<?php
/*
Plugin Name: Ecwid Shopping Cart
Plugin URI: http://www.ecwid.com/ 
Description: Ecwid is free full-fledged shopping cart. It can be easily integreted with any Wordpress blog and takes less than 5 minutes to set up.
Author: Ecwid Team
Version: 0.1
Author URI: http://www.ecwid.com/
*/



register_activation_hook( __FILE__, 'ecwid_store_activate' );
register_deactivation_hook( __FILE__, 'ecwid_store_deactivate' );

add_action('admin_init', 'ecwid_settings_api_init');
add_action('admin_notices', 'ecwid_is_enabled_message');
add_action('admin_menu', 'ecwid_options_add_page');
add_action('wp_dashboard_setup', 'ecwid_add_dashboard_widgets' );

add_shortcode('ecwid_script', 'ecwid_script_shortcode');



function ecwid_script_shortcode() {
	$store_id = get_ecwid_store_id();
	return "<script type='text/javascript' src='http://app.ecwid.com/script.js?$store_id'></script>";
}

function ecwid_store_activate() {
	$my_post = array();
	$content = <<<EOT
		<!-- Ecwid code start -->

		<!-- Please do not remove the line below otherwise your Ecwid shopping cart will not work. 
		Start of special code: -->
		[ecwid_script]
		<!-- End of special code. -->

        <!-- Feel free to modify the code below: add new widgets, alter the existing ones. -->
		<div><script type='text/javascript'> xCategories(); </script></div>
		<div><script type='text/javascript'> xProductBrowser("itemsPerRow=3","itemsPerPage=9","searchResultsItemsPerPage=10"); </script></div>
		<div><script type='text/javascript'> xMinicart("style=","layout=attachToCategories"); </script></div>
		<!-- Ecwid code end -->

EOT;
	add_option("ecwid_store_page_id", '', '', 'yes');	
	add_option("ecwid_store_id", '1003', '', 'yes');
	$id = get_option("ecwid_store_page_id");	
	$_tmp_page = null;
	if (!empty($id) and ($id > 0)) { 
		$_tmp_page = get_page($id);
	}
	if ($_tmp_page !== null) {
		$my_post = array();
		$my_post['ID'] = $id;
		$my_post['post_status'] = 'publish';
		wp_update_post( $my_post );

	} else {
		$my_post['post_title'] = 'Store';
		$my_post['post_content'] = $content;
		$my_post['post_status'] = 'publish';
		$my_post['post_author'] = 1;
		$my_post['post_type'] = 'page';
		$id =  wp_insert_post( $my_post );
		update_option('ecwid_store_page_id', $id);
	}

}
function ecwid_is_enabled_message() {
		if (get_ecwid_store_id() != 1003)
			return;
		$ecwid_page_id = get_option("ecwid_store_page_id");
		$page_url = get_page_link($ecwid_page_id);
		echo "<div id='' class='updated fade'><p><strong>Ecwid shopping cart is almost ready</strong>.  Please visit <a href=\"$page_url\">the created  page</a> to see your store with demo products. In order to finish the installation, please go to the <a href=\"options-general.php?page=ecwid_options_page\"><strong>Ecwid settings</strong></a> and configure the plugin.</p></div>";
	}

function ecwid_store_deactivate() {
	$ecwid_page_id = get_option("ecwid_store_page_id");
	$_tmp_page = null;
	if (!empty($ecwid_page_id) and ($ecwid_page_id > 0)) {
		$_tmp_page = get_page($id);
		if ($_tmp_page !== null) {
			$my_post = array();
			$my_post['ID'] = $ecwid_page_id;
			$my_post['post_status'] = 'draft';
			wp_update_post( $my_post );
		} else {
			update_option('ecwid_store_page_id', '');	
		}
	}

}

 
 function ecwid_settings_api_init() {
  register_setting( 'ecwid_options_page', 'ecwid_store_id','intval' );
} 

function ecwid_options_add_page() {
	add_options_page('Ecwid shopping cart settings', 'Ecwid shopping cart', 'manage_options', 'ecwid_options_page', 'ecwid_options_do_page');
}

function ecwid_options_do_page() {
	$store_id = get_ecwid_store_id(); 
    ?>
    <div class="wrap">
        <h2>Ecwid settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('ecwid_options_page'); ?>
            <table class="form-table">
                            <tr valign="top"><th scope="row">Store ID:</th>
                    <td><input type="text" name="ecwid_store_id" value="<?php if ($store_id != 1003) echo $store_id; ?>" /></td>
                </tr>
            </table>
            <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
    <style>
        ul#ecwid-instruction-ul li {
            padding-bottom:10px;
        }
    </style> 
    <div id="ecwid-instruction" >
        <h4>Instruction on how to get your free Store ID</h4>
    <ul style="padding-left:30px;list-style-type:disc;" id="ecwid-instruction-ul">
        <li>Go to the <a href="https://my.ecwid.com/cp/#register" target="_blank">Ecwid control panel</a>. Open this URL:  <a href="https://my.ecwid.com/cp/#register" target="_blank">https://my.ecwid.com/cp/#register</a>.  You will get to 'Sign in or Register' form.</li>
        <li> Register an account at Ecwid. Use section "Using Ecwid account" for that. The registration is free.<br />
         Or you can login using your account at Gmail, Facebook, Twitter, Yahoo, or another provider. Choose one from the list of the providers (click on 'More providers' if you don't see your provider there). Click on the provider logo, you will be redirected to the account login page. Submit your username/password there to login to your Ecwid. 
         <br />
        Note: the login might take several seconds. Please, be patient.</li>

        <li> When you login the "<a href="https://my.ecwid.com/cp/CP.html#catalog=-1&products=list&profile=-1&settings=4&t1=-1&t2=0&dashboard=-1" target="_blank" >Dashboard</a>" page will appear with 'Getting Started' section.</li>

        <li> Find the Product Browser Widget Code textbox with some lines of HTML/JavaScript code in there. This is the Ecwid integration code.</li>

        <li> Look through the code lines, find this line <br />
        <span style="background-color:#d3e9e9;">src='http://app.ecwid.com/script.js?<strong>2341</strong>' </span>
    <br />
    On your page there will be not '<strong>2341</strong>', but <strong>another number</strong>. This number is your <strong>store ID</strong>. </li>

    </ul>
    If you have any questions, feel free to ask them on <a href="http://www.ecwid.com/forums/">Ecwid forums</a> or <a href="http://www.ecwid.com/contact-us.html">contact Ecwid team</a>.
 </div>
        </form>



    </div>
    <?php   
} 
  
function get_ecwid_store_id() {
        $store_id = get_option('ecwid_store_id');
        if (empty($store_id))
                $store_id = 1003;
	return $store_id;
} 
 
function ecwid_setting_callback_function() {
		$store_id = get_ecwid_store_id();
	echo "<input  name='ecwid_setting_name' id='gv_thumbnails_insert_into_excerpt' value='$store_id' class='code' /> Explanation text";
} 


function ecwid_dashboard_widget_function() {
echo "<a href=\"https://my.ecwid.com/\" target=\"_blank\">Go to the Ecwid Control Panel</a>";
} 

// Create the function use in the action hook

function ecwid_add_dashboard_widgets() {
	wp_add_dashboard_widget('ecwid_dashboard_widget','Ecwid Control Panel', 'ecwid_dashboard_widget_function');	
} 


?>
