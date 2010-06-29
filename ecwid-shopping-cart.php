<?php
/*
Plugin Name: Ecwid Shopping Cart
Plugin URI: http://www.ecwid.com/ 
Description: Ecwid is a free full-featured shopping cart. It can be easily integreted with any Wordpress blog and takes less than 5 minutes to set up.
Author: Ecwid Team
Version: 0.7
Author URI: http://www.ecwid.com/
*/

register_activation_hook( __FILE__, 'ecwid_store_activate' );
register_deactivation_hook( __FILE__, 'ecwid_store_deactivate' );

define("APP_ECWID_COM","app.ecwid.com");

if ( is_admin() ){ 
  add_action('admin_init', 'ecwid_settings_api_init');
  add_action('admin_notices', 'ecwid_show_admin_message');
  add_action('admin_menu', 'ecwid_options_add_page');
  add_action('wp_dashboard_setup', 'ecwid_add_dashboard_widgets' );
} else {
  add_shortcode('ecwid_script', 'ecwid_script_shortcode');
  add_shortcode('ecwid_minicart', 'ecwid_minicart_shortcode');
  add_shortcode('ecwid_searchbox', 'ecwid_searchbox_shortcode');
  add_shortcode('ecwid_categories', 'ecwid_categories_shortcode');
  add_shortcode('ecwid_productbrowser', 'ecwid_productbrowser_shortcode');
}



function ecwid_script_shortcode() {
  $ecwid_protocol = get_ecwid_protocol();
	$store_id = get_ecwid_store_id();
	$s =  "<div><script type=\"text/javascript\" src=\"$ecwid_protocol://" . APP_ECWID_COM . "/script.js?$store_id\"></script></div>";
	return $s;
}


function ecwid_minicart_shortcode() {
    $ecwid_enable_minicart = get_option('ecwid_enable_minicart');
    $ecwid_show_categories = get_option('ecwid_show_categories');
    if (!empty($ecwid_enable_minicart) && !empty($ecwid_show_categories)) {
        $s = <<<EOT
<div><script type="text/javascript"> xMinicart("style=","layout=attachToCategories"); </script></div>
EOT;
        return $s;
    } else {
        return "";
    }
}
function ecwid_searchbox_shortcode() {
    $ecwid_show_search_box = get_option('ecwid_show_search_box');
    if (!empty($ecwid_show_search_box)) {
        $s = <<<EOT
<div><script type="text/javascript"> xSearchPanel("style="); </script></div>
EOT;
        return $s;
    } else {
        return "";
    }
}

function ecwid_categories_shortcode() {
    $ecwid_show_categories = get_option('ecwid_show_categories');
    if (!empty($ecwid_show_categories)) {
        $s = <<<EOT
<div><script type="text/javascript"> xCategories("style="); </script></div>
EOT;
        return $s;
    } else {
        return "";
    }
}

function ecwid_productbrowser_shortcode() {
    $store_id = get_ecwid_store_id();
    $list_of_views = array('list','grid','table');

    $ecwid_pb_categoriesperrow = get_option('ecwid_pb_categoriesperrow');
    $ecwid_pb_productspercolumn_grid = get_option('ecwid_pb_productspercolumn_grid');
    $ecwid_pb_productsperrow_grid = get_option('ecwid_pb_productsperrow_grid');
    $ecwid_pb_productsperpage_list = get_option('ecwid_pb_productsperpage_list');
    $ecwid_pb_productsperpage_table = get_option('ecwid_pb_productsperpage_table');
    $ecwid_pb_defaultview = get_option('ecwid_pb_defaultview');
    $ecwid_pb_searchview = get_option('ecwid_pb_searchview');

    $ecwid_mobile_catalog_link = get_option('ecwid_mobile_catalog_link');
    $ecwid_default_category_id = get_option('ecwid_default_category_id');

    $ecwid_noscript_seo_catalog = get_option('ecwid_noscript_seo_catalog');

    if (empty($ecwid_pb_categoriesperrow)) {
        $ecwid_pb_categoriesperrow = 3;
    }
    if (empty($ecwid_pb_productspercolumn_grid)) {
        $ecwid_pb_productspercolumn_grid = 3;
    }
    if (empty($ecwid_pb_productsperrow_grid)) {
        $ecwid_pb_productsperrow_grid = 3;
    }
    if (empty($ecwid_pb_productsperpage_list)) {
        $ecwid_pb_productsperpage_list = 10;
    }
    if (empty($ecwid_pb_productsperpage_table)) {
        $ecwid_pb_productsperpage_table = 20;
    }

    if (empty($ecwid_pb_defaultview) || !in_array($ecwid_pb_defaultview, $list_of_views)) {
        $ecwid_pb_defaultview = 'grid';
    }
    if (empty($ecwid_pb_searchview) || !in_array($ecwid_pb_searchview, $list_of_views)) {
        $ecwid_pb_searchview = 'list';
    }

    if (empty($ecwid_mobile_catalog_link)) {
        $ecwid_mobile_catalog_link = "http://" . APP_ECWID_COM . "/jsp/{$store_id}/catalog";
    }

    if (empty($ecwid_default_category_id)) {
        $ecwid_default_category_str = '';
    } else {
        $ecwid_default_category_str = ',"defaultCategoryId='. $ecwid_default_category_id .'"';
    }

    
    $ecwid_open_product = '';
    if (!empty($ecwid_noscript_seo_catalog)) {
      
      if (!empty($_GET['ecwid_product_id'])) {
        $ecwid_open_product = '<script>document.location.hash = "ecwid:category=0&mode=product&product='. intval($_GET['ecwid_product_id']) .'";</script>';
      } elseif (!empty($_GET['ecwid_category_id'])) {
        $ecwid_default_category_str = ',"defaultCategoryId='. intval($_GET['ecwid_category_id']) .'"';
      }
      include_once(ABSPATH . 'wp-content/plugins/ecwid-shopping-cart/lib/ecwid_catalog.php');
      $noscript_str = '<noscript>'. show_ecwid_catalog($store_id) . '</noscript>';
    } else {
      $noscript_str = "<noscript>Your browser does not support JavaScript.<a href=\"{$ecwid_mobile_catalog_link}\">HTML version of this store</a></noscript>";
    }
    
        $s = <<<EOT
<div> <script type="text/javascript"> xProductBrowser("categoriesPerRow=$ecwid_pb_categoriesperrow","views=grid($ecwid_pb_productspercolumn_grid,$ecwid_pb_productsperrow_grid) list($ecwid_pb_productsperpage_list) table($ecwid_pb_productsperpage_table)","categoryView=$ecwid_pb_defaultview","searchView=$ecwid_pb_searchview","style="$ecwid_default_category_str);</script></div>
{$noscript_str}
$ecwid_open_product
EOT;
        return $s;
}



function ecwid_store_activate() {
	$my_post = array();
	$content = <<<EOT
		<!-- Ecwid code start v0.2. Please do not remove the line below otherwise your Ecwid shopping cart will not work. -->
		[ecwid_script] [ecwid_minicart] [ecwid_searchbox] [ecwid_categories] [ecwid_productbrowser]
		<!-- Ecwid code end -->

EOT;
  	add_option("ecwid_store_page_id", '', '', 'yes');	
  	add_option("ecwid_store_id", '1003', '', 'yes');
    
    add_option("ecwid_enable_minicart", 'Y', '', 'yes');
    add_option("ecwid_show_categories", 'Y', '', 'yes');
    add_option("ecwid_show_search_box", '', '', 'yes');


    add_option("ecwid_pb_categoriesperrow", '3', '', 'yes');

    add_option("ecwid_pb_productspercolumn_grid", '3', '', 'yes');
    add_option("ecwid_pb_productsperrow_grid", '3', '', 'yes');
    add_option("ecwid_pb_productsperpage_list", '10', '', 'yes');
    add_option("ecwid_pb_productsperpage_table", '20', '', 'yes');

    add_option("ecwid_pb_defaultview", 'grid', '', 'yes');
    add_option("ecwid_pb_searchview", 'list', '', 'yes');

    add_option("ecwid_enable_ssl", '', '', 'yes');  
    
    add_option("ecwid_mobile_catalog_link", '', '', 'yes');  
    add_option("ecwid_default_category_id", '', '', 'yes');  
      
    add_option("ecwid_noscript_seo_catalog", '', '', 'yes');        
    
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
function ecwid_show_admin_message() {

  if (get_ecwid_store_id() != 1003) {
    return;
  }	else {
		$ecwid_page_id = get_option("ecwid_store_page_id");
		$page_url = get_page_link($ecwid_page_id);
		echo "<div id='' class='updated fade'><p><strong>Ecwid shopping cart is almost ready</strong>.  Please visit <a href=\"$page_url\">the created  page</a> to see your store with demo products. In order to finish the installation, please go to the <a href=\"options-general.php?page=ecwid_options_page\"><strong>Ecwid settings</strong></a> and configure the plugin.</p></div>";
		}
	}

function ecwid_store_deactivate() {
	$ecwid_page_id = get_option("ecwid_store_page_id");
	$_tmp_page = null;
	if (!empty($ecwid_page_id) and ($ecwid_page_id > 0)) {
		$_tmp_page = get_page($ecwid_page_id);
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

function ecwid_abs_intval($value) {
    return abs(intval($value));
}

function ecwid_settings_api_init() {
    register_setting('ecwid_options_page', 'ecwid_store_id','ecwid_abs_intval' );
    register_setting('ecwid_options_page', 'ecwid_enable_minicart');

    register_setting('ecwid_options_page', 'ecwid_show_categories');
    register_setting('ecwid_options_page', 'ecwid_show_search_box');

    register_setting('ecwid_options_page', 'ecwid_pb_categoriesperrow', 'ecwid_abs_intval');
    register_setting('ecwid_options_page', 'ecwid_pb_productspercolumn_grid', 'ecwid_abs_intval');
    register_setting('ecwid_options_page', 'ecwid_pb_productsperrow_grid', 'ecwid_abs_intval');
    register_setting('ecwid_options_page', 'ecwid_pb_productsperpage_list', 'ecwid_abs_intval');
    register_setting('ecwid_options_page', 'ecwid_pb_productsperpage_table', 'ecwid_abs_intval');
    register_setting('ecwid_options_page', 'ecwid_pb_defaultview');
    register_setting('ecwid_options_page', 'ecwid_pb_searchview');
    register_setting('ecwid_options_page', 'ecwid_enable_ssl');
    
    register_setting('ecwid_options_page', 'ecwid_mobile_catalog_link');
    register_setting('ecwid_options_page', 'ecwid_default_category_id');
    
    register_setting('ecwid_options_page', 'ecwid_noscript_seo_catalog');
    
} 

function ecwid_options_add_page() {
	add_options_page('Ecwid shopping cart settings', 'Ecwid shopping cart', 'manage_options', 'ecwid_options_page', 'ecwid_options_do_page');
}

function ecwid_options_do_page() {
	$store_id = get_ecwid_store_id(); 
    $ecwid_enable_minicart = get_option('ecwid_enable_minicart');
    $ecwid_show_categories = get_option('ecwid_show_categories');
    $ecwid_show_search_box = get_option('ecwid_show_search_box');

    $ecwid_pb_categoriesperrow = get_option('ecwid_pb_categoriesperrow');
    $ecwid_pb_productspercolumn_grid = get_option('ecwid_pb_productspercolumn_grid');
    $ecwid_pb_productsperrow_grid = get_option('ecwid_pb_productsperrow_grid');
    $ecwid_pb_productsperpage_list = get_option('ecwid_pb_productsperpage_list');
    $ecwid_pb_productsperpage_table = get_option('ecwid_pb_productsperpage_table');
    $ecwid_pb_defaultview = get_option('ecwid_pb_defaultview');
    $ecwid_pb_searchview = get_option('ecwid_pb_searchview');
    
    $ecwid_mobile_catalog_link = get_option('ecwid_mobile_catalog_link');
    $ecwid_default_category_id = get_option('ecwid_default_category_id');
    $ecwid_noscript_seo_catalog = get_option('ecwid_noscript_seo_catalog');
    $ecwid_enable_ssl = get_option('ecwid_enable_ssl');
    $ecwid_page_id = get_option("ecwid_store_page_id");
   
    $ecwid_noscript_seo_catalog_disabled = false;
    $ecwid_noscript_seo_catalog_message = '<a href="http://kb.ecwid.com/Inline-SEO-Catalog" target="_blank">How it works</a>';
    $ecwid_settings_message = false;
    
    if ($store_id == 1003) {
      $ecwid_noscript_seo_catalog_disabled = true;
      $ecwid_noscript_seo_catalog_message = 'This option requires using Ecwid Product API. In order to enable it, please set up your Store ID first. Please note, that API is available for the <a href="http://www.ecwid.com/compare-plans.html" target="_blank">paid Ecwid accounts</a> only.';
      if (!empty($ecwid_noscript_seo_catalog)) {
        update_option('ecwid_noscript_seo_catalog', '');
        $ecwid_noscript_seo_catalog = '';
      }
    } # demo store id
    
    else if ($store_id != 1003 && empty($ecwid_noscript_seo_catalog)) {
      $ecwid_noscript_seo_catalog_message = '<a href="http://kb.ecwid.com/Inline-SEO-Catalog" target="_blank">How Inline Seo Catalog works</a>. This option requires using Ecwid API which is available for the <a href="http://www.ecwid.com/compare-plans.html" target="_blank">paid accounts</a> only.';
    }
    else if ($store_id != 1003 && !empty($ecwid_noscript_seo_catalog)) {
    
      include_once(ABSPATH . 'wp-content/plugins/ecwid-shopping-cart/lib/ecwid_product_api.php');
      $api = new EcwidProductApi($store_id);
      if (!empty($api)) {
        $store_profile = $api->get_profile();
        
        if ($api->error_code == 403) {
          #free account, consider upgrade
          $ecwid_noscript_seo_catalog_disabled = true;
          $ecwid_noscript_seo_catalog_message = 'This option requires using Ecwid API which is available for the <a href="http://www.ecwid.com/compare-plans.html" target="_blank">paid accounts</a> only. Please consider upgrading your account to use it.'; 
          update_option('ecwid_noscript_seo_catalog', '');
          $ecwid_noscript_seo_catalog = '';
          $ecwid_settings_message = 'The "Inline HTML catalog" option requires using Ecwid API which is available for the <a href="http://www.ecwid.com/compare-plans.html" target="_blank">paid accounts</a> only. Please consider upgrading your account to use it.';
          
        } 
        else if ($api->error_code == 404) {
          $ecwid_noscript_seo_catalog_message = 'We cannot connect to Ecwid servers using your Store ID. Is it correct?'; 
          $ecwid_settings_message = 'We cannot connect to Ecwid servers using your Store ID. Is it correct?';
         
        }
       
        else if($api->error_code != '') {
          $ecwid_noscript_seo_catalog_message = 'Connection to Ecwid servers failed. Error code: '. $api->error_code; 
          $ecwid_settings_message = 'Connection to Ecwid servers failed. Error code: '. $api->error_code;
          if ($api->error_code == 0) {
            $ecwid_noscript_seo_catalog_message .= '&nbsp;'. $api->error; 
            $ecwid_settings_message .= '&nbsp;'. $api->error;
          }
        }
      } # no $api
      else {
         $ecwid_settings_message = 'We cannot include some essential files of Ecwid Shopping Cart module. It looks like it\'s corrupted. Try to re-install the module and contact Ecwid Team if it doesn\'t help.';
      }
    }
        
    $_tmp_page = null;
    $disabled = false;
    if (!empty($ecwid_page_id) and ($ecwid_page_id > 0)) {
        $_tmp_page = get_page($ecwid_page_id);
        $content = $_tmp_page->post_content;
        if ( (strpos($content, "[ecwid_productbrowser]") === false) && (strpos($content, "xProductBrowser") !== false) )
               $disabled = true;
    }

    if ($disabled)
        $disabled_str = 'disabled = "disabled"';
    else
        $disabled_str = "";


    ?>
    <div class="wrap">
    		<?php if ($ecwid_settings_message)
    		echo "<div id='' class='updated fade'><p><strong>Error.</strong>&nbsp;$ecwid_settings_message</p></div>";
    		?>
        <h2>Ecwid settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('ecwid_options_page'); ?>
            <table class="form-table">
            <tr><th colspan="2" style="padding:0px;margin:0px;"><h3 style="padding:0px;margin:0px;">General</h3></th></tr>
                            <tr><th scope="row"><a href="http://kb.ecwid.com/Instruction-on-how-to-get-your-free-Store-ID-(for-WordPress)" target="_blank">Store ID</a>:</th>
                    <td><input type="text" name="ecwid_store_id" value="<?php if ($store_id != 1003) echo $store_id; ?>" /></td>
		    </tr>
		        <tr><th scope="row">
    <label for="ecwid_show_categories">Show horizontal categories?</label> </th>
    <td><input type="checkbox" id="ecwid_show_categories" name="ecwid_show_categories" <?php if (!empty($ecwid_show_categories)) echo "checked=\"checked\""; echo $disabled_str; ?> />
</td>
            </tr>
    <tr><th scope="row">
    <label for="ecwid_show_search_box">Show search box?</label> </th>
        <td><input type="checkbox" id="ecwid_show_search_box" name="ecwid_show_search_box" <?php if (!empty($ecwid_show_search_box)) echo "checked=\"checked\"";?> <?php echo $disabled_str;?> />
</td>
            </tr>
         
    <tr><th scope="row">
<label for="ecwid_enable_minicart">Enable minicart attached to horizontal categories?</label></th>
    <td><input type="checkbox" name="ecwid_enable_minicart" id="ecwid_enable_minicart" <?php if (!empty($ecwid_enable_minicart) && !empty($ecwid_show_categories)) echo "checked=\"checked\"";?> 
<?php if (empty($ecwid_show_categories)) { 
     echo 'disabled = "disabled"';
   }
   else { 
     echo $disabled_str;
   } ?> />
&nbsp;&nbsp;&nbsp;&nbsp;<img src="//www.ecwid.com/wp-content/uploads/ecwid_wp_attention.gif" alt="">&nbsp;If you added minicart to your blog's sidebar, please disable this option.

</td>
            </tr>

            <tr><th colspan="2" style="padding:0px;margin:0px;"><h3 style="padding:0px;margin:0px;">Appearance</h3></th></tr>

                            <tr><th scope="row"><label for="ecwid_pb_categoriesperrow">Categories per row</label></th>
                            <td><input type="text" id="ecwid_pb_categoriesperrow" name="ecwid_pb_categoriesperrow" value="<?php  echo $ecwid_pb_categoriesperrow; ?>" <?php echo $disabled_str;?> /></td>
            </tr>

                            <tr><th scope="row"><label for="ecwid_pb_productspercolumn_grid">Products per column in grid mode</th>
                            <td><input type="text" id="ecwid_pb_productspercolumn_grid" name="ecwid_pb_productspercolumn_grid" value="<?php  echo $ecwid_pb_productspercolumn_grid; ?>" <?php echo $disabled_str;?> /></td>
            </tr>                            
            
            <tr><th scope="row"><label for="ecwid_pb_productsperrow_grid">Products per row in grid mode</label></th>
                            <td><input type="text" id="ecwid_pb_productsperrow_grid" name="ecwid_pb_productsperrow_grid" value="<?php  echo $ecwid_pb_productsperrow_grid; ?>" <?php echo $disabled_str;?> /></td>
            </tr>                        

    <tr><th scope="row"><label for="ecwid_pb_productsperpage_list">Products per page in list mode</label></th>
                            <td><input type="text" id="ecwid_pb_productsperpage_list" name="ecwid_pb_productsperpage_list" value="<?php  echo $ecwid_pb_productsperpage_list; ?>" <?php echo $disabled_str;?> /></td>
            </tr>

                            <tr><th scope="row"><label for="ecwid_pb_productsperpage_table">Products per page in table mode</label></th>
                            <td><input type="text" id="ecwid_pb_productsperpage_table" name="ecwid_pb_productsperpage_table" value="<?php  echo $ecwid_pb_productsperpage_table; ?>" <?php echo $disabled_str;?> /></td>
            </tr>


                            <tr><th scope="row"><label for="ecwid_pb_defaultview">Default view mode on product pages</label></th>
                            <td>
				<select id="ecwid_pb_defaultview" name="ecwid_pb_defaultview" <?php echo $disabled_str;?> >
					<option value="grid" <?php if($ecwid_pb_defaultview == 'grid') echo 'selected="selected"' ?> >Grid mode</option>
					<option value="list" <?php if($ecwid_pb_defaultview == 'list') echo 'selected="selected"' ?> >List mode</option>
					<option value="table" <?php if($ecwid_pb_defaultview == 'table') echo 'selected="selected"' ?> >Table mode</option>
				</select>
</td>
            </tr>

                            <tr><th scope="row"><label for="ecwid_pb_searchview">Default view mode on search results</label></th>
                            <td>
				<select id="ecwid_pb_searchview" name="ecwid_pb_searchview" <?php echo $disabled_str;?> >
					<option value="grid" <?php if($ecwid_pb_searchview == 'grid') echo 'selected="selected"' ?> >Grid mode</option>
					<option value="list" <?php if($ecwid_pb_searchview == 'list') echo 'selected="selected"' ?> >List mode</option>
					<option value="table" <?php if($ecwid_pb_searchview == 'table') echo 'selected="selected"' ?> >Table mode</option>
				</select>
</td>
            </tr>

         <tr><th colspan="2" style="padding:0px;margin:0px;"><h3 style="padding:0px;margin:0px;">SEO</h3></th></tr>

                <tr><th scope="row"><label for="ecwid_noscript_seo_catalog">
Enable the inline plain-HTML catalog(better SEO)</label>
</th>
    <td><input id="ecwid_noscript_seo_catalog" type="checkbox" name="ecwid_noscript_seo_catalog" <?php if (!empty($ecwid_noscript_seo_catalog)) echo "checked=\"checked\""; if(!empty($ecwid_noscript_seo_catalog_disabled)) echo "disabled=\"disabled\""; ?> />
&nbsp;&nbsp;&nbsp;&nbsp;<img src="//www.ecwid.com/wp-content/uploads/ecwid_wp_attention.gif" alt="">&nbsp; <?php echo $ecwid_noscript_seo_catalog_message; ?>

</td>            </tr>    
                                      <tr><th scope="row"><label for="ecwid_mobile_catalog_link">
Full link to your mobile catalog</label>
</th>
    <td><input id="ecwid_mobile_catalog_link" type="text" name="ecwid_mobile_catalog_link" value="<?php  echo $ecwid_mobile_catalog_link; ?>" />
&nbsp;&nbsp;&nbsp;&nbsp;<img src="//www.ecwid.com/wp-content/uploads/ecwid_wp_attention.gif" alt="">&nbsp;For example <em>http://mdemo.ecwid.com</em>.&nbsp;<a href="http://kb.ecwid.com/Mobile-Catalog" target="_blank">Information about Ecwid and mobile catalogs.</a>

</td>            </tr>
            
                        <tr><th colspan="2" style="padding:0px;margin:0px;"><h3 style="padding:0px;margin:0px;">Advanced</h3></th></tr>
            
                <tr><th scope="row"><label for="ecwid_enable_ssl">
Enable the following option, if you use Ecwid on a secure HTTPS page</label>
</th>
    <td><input id="ecwid_enable_ssl" type="checkbox" name="ecwid_enable_ssl" <?php if (!empty($ecwid_enable_ssl)) echo "checked=\"checked\"";?> />
&nbsp;&nbsp;&nbsp;&nbsp;<img src="//www.ecwid.com/wp-content/uploads/ecwid_wp_attention.gif" alt="">&nbsp;<a href="http://kb.ecwid.com/SSL-HTTPS" target="_blank">Information about Ecwid and SSL/HTTPS</a>

</td>            </tr>
            
           
                           <tr><th scope="row"><label for="ecwid_default_category_id">
Default category ID</label>
</th>
    <td><input id="ecwid_default_category_id" type="text" name="ecwid_default_category_id" value="<?php  echo $ecwid_default_category_id; ?>"/>
&nbsp;&nbsp;&nbsp;&nbsp;<img src="//www.ecwid.com/wp-content/uploads/ecwid_wp_attention.gif" alt="">&nbsp;<a href="http://kb.ecwid.com/Default-category-for-product-browser" target="_blank">What is it?</a>

</td>            </tr>
           
            
            </table>
            <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>

    <style>
        ul#ecwid-instruction-ul li, ul#ecwid-need-manual-editing-ul li {
            padding-bottom:10px;
        }
    </style> 


<?php 
    if ($disabled) {
?>

    <div id="ecwid-need-manual-editing" >
        <h4>Why I cannot change some options?</h4>

Most likely you've upgraded <strong>Ecwid Shopping Cart</strong> plugin from <strong>v0.1</strong> to the next one. The Ecwid integration code was changed in this new version. So if you want to use the new options you should update the code manually. This procedure isn't that complex and will take just a few minutes.
<br />
    <ul style="padding-left:30px;list-style-type:disc;" id="ecwid-need-manual-editing-ul">
<li>
Open <a href="page.php?action=edit&post=<?php echo intval($ecwid_page_id); ?>">your store page for editing</a>.
</li>

<li>
Make sure that you change edit mode to "HTML" instead of "Visual".
</li>


<li>
Replace these lines:<br /><br />

<pre style="background-color:#d3e9e9;">
        &lt;!-- Ecwid code start --&gt;
        &lt;!-- Please do not remove the line below otherwise your Ecwid shopping cart will not work.
        Start of special code: --&gt;
        [ecwid_script]
        &lt;!-- End of special code. --&gt;

        &lt;!-- Feel free to modify the code below: add new widgets, alter the existing ones. --&gt;
        &lt;div&gt;&lt;script type=&quot;text/javascript&quot;&gt; xCategories(); &lt;/script&gt;&lt;/div&gt;
        &lt;div&gt;&lt;script type=&quot;text/javascript&quot;&gt; xProductBrowser(&quot;itemsPerRow=3&quot;,&quot;itemsPerPage=9&quot;,&quot;searchResultsItemsPerPage=10&quot;); &lt;/script&gt;&lt;/div&gt;
        &lt;div&gt;&lt;script type=&quot;text/javascript&quot;&gt; xMinicart(&quot;style=&quot;,&quot;layout=attachToCategories&quot;); &lt;/script&gt;&lt;/div&gt;
        &lt;!-- Ecwid code end --&gt;
</pre>
<br /><br />
with these ones:
<br /><br />
<pre style="background-color:#d3e9e9;">
        &lt;!-- Ecwid code start v0.2. Please do not remove the line below otherwise your Ecwid shopping cart will not work. --&gt;
        [ecwid_script] [ecwid_minicart] [ecwid_searchbox] [ecwid_categories] [ecwid_productbrowser]
        &lt;!-- Ecwid code end --&gt;
</pre>
</li>

<li>Save the changes</li>

</ul>

</div> 


<?php
    
    }

?>

<?php 
if ($store_id == '1003') {
?>
    <div id="ecwid-instruction" >
<h4>Instruction on how to get your free Store ID</h4>
<ul style="padding-left:30px;list-style-type:disc;" id="ecwid-instruction-ul">
    <li>Go to the <a target="_blank" href="https://my.ecwid.com/cp/#register">Ecwid control panel</a>. Open this URL: <a target="_blank" href="https://my.ecwid.com/cp/#register">https://my.ecwid.com/cp/#register</a>. You will get to 'Sign In or Register' form.</li>
    <li>Register an account at Ecwid. Use section &quot;Using Ecwid account&quot; for that. The registration is free.
    <p>Or you can log in using your account at Gmail, Facebook, Twitter, Yahoo, or another provider. Choose one from the list of the providers (click on 'More providers' if you don't see your provider there). Click on the provider logo, you will be redirected to the account login page. Submit your username/password there to login to your Ecwid.</p>
    <p>Note: the login might take several seconds. Please, be patient.</p>
    </li>
    <li>Look at the right bottom corner of the page.</li>
    <li>You will see the&nbsp;<span style="background-color:#d3e9e9;">&quot;Store ID: <strong>NNNNNN</strong>&quot;</span> text, where <strong>NNNNNN</strong> is your <strong>Store ID</strong>.<br />
    <p>For example if the text is&nbsp;<span style="background-color:#d3e9e9;">Store ID:</span> <strong><span style="background-color:#d3e9e9;">1003</span></strong>, your Store ID is <strong>1003</strong>. &nbsp;</p><br />
    You will also get your Store ID by email.
    </li>
</ul>
<p>If you have any questions, feel free to ask them on <a href="http://www.ecwid.com/forums/">Ecwid forums</a> or <a href="http://www.ecwid.com/contact-us.html">contact Ecwid team</a>.</p>
 </div>
 <?php 
 }
 ?>
        </form>



    </div>
    <?php   
} 
  
function get_ecwid_store_id() {
    static $store_id = null;
    if (is_null($store_id)) {
        $store_id = get_option('ecwid_store_id');
        if (empty($store_id))
          $store_id = 1003;
    }
	return $store_id;
} 
 
function get_ecwid_protocol() {
        static $ecwid_enable_ssl = null;
        if (is_null($ecwid_enable_ssl)) {
            $ecwid_enable_ssl = get_option('ecwid_enable_ssl');
        }
        if (empty($ecwid_enable_ssl)) {
            return "http";
        }
        else {
            return "https";
        }
} 
 
function ecwid_dashboard_widget_function() {
echo "<a href=\"https://my.ecwid.com/\" target=\"_blank\">Go to the Ecwid Control Panel</a><br /><br /><a href=\"http://kb.ecwid.com/\" target=\"_blank\">Ecwid Knowledge Base</a>&nbsp;|&nbsp;<a href=\"http://www.ecwid.com/forums/\" target=\"_blank\">Ecwid Forums</a>";
} 

function ecwid_add_dashboard_widgets() {
	wp_add_dashboard_widget('ecwid_dashboard_widget','Ecwid Links', 'ecwid_dashboard_widget_function');	
} 



class EcwidMinicartWidget extends WP_Widget {

    function EcwidMinicartWidget() {
    $widget_ops = array('classname' => 'widget_ecwid_minicart', 'description' => __( "Your store's minicart") );
    $this->WP_Widget('ecwidminicart', __('Ecwid Minicart'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

        echo $before_widget;

        if ( $title )
            echo $before_title . $title . $after_title;

        $store_id = get_ecwid_store_id();
        $ecwid_protocol = get_ecwid_protocol();
        echo "<div><script type=\"text/javascript\" src=\"$ecwid_protocol://" . APP_ECWID_COM . "/script.js?$store_id\"></script>";


        $ecwid_page_id = get_option("ecwid_store_page_id");
        $page_url = get_page_link($ecwid_page_id);
        $_tmp_page = get_page($ecwid_page_id);
        if (!empty($page_url) && $_tmp_page != null)
            echo "<script>var ecwid_ProductBrowserURL = \"$page_url\";</script>";
        echo <<<EOT
          <script type="text/javascript"> xMinicart("style="); </script>
          </div>
EOT;

        echo $after_widget;
    }

    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['title'] = strip_tags(stripslashes($new_instance['title']));

    return $instance;
  }

    function form($instance){
      $instance = wp_parse_args( (array) $instance, array('title'=>'') );

      $title = htmlspecialchars($instance['title']);

      echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width:100%;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
  }

}

class EcwidSearchWidget extends WP_Widget {

    function EcwidSearchWidget() {
    $widget_ops = array('classname' => 'widget_ecwid_search', 'description' => __( "Your store's search box") );
    $this->WP_Widget('ecwidsearch', __('Ecwid Search Box'), $widget_ops);
    }

    function widget($args, $instance) {
      extract($args);
      $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

      echo $before_widget;

      if ( $title )
      echo $before_title . $title . $after_title;

        $store_id = get_ecwid_store_id();
        $ecwid_protocol = get_ecwid_protocol();
        echo "<div><script type=\"text/javascript\" src=\"$ecwid_protocol://" . APP_ECWID_COM . "/script.js?$store_id\"></script>";
	$ecwid_page_id = get_option("ecwid_store_page_id");
        $page_url = get_page_link($ecwid_page_id);
                $_tmp_page = get_page($ecwid_page_id);
                if (!empty($page_url) && $_tmp_page != null)
		echo "<script>var ecwid_ProductBrowserURL = \"$page_url\";</script>";
      echo <<<EOT
	<script type="text/javascript"> xSearchPanel("style="); </script>	      
	</div>
EOT;
      
echo $after_widget;
  }

    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['title'] = strip_tags(stripslashes($new_instance['title']));

    return $instance;
  }

    function form($instance){
      $instance = wp_parse_args( (array) $instance, array('title'=>'') );

      $title = htmlspecialchars($instance['title']);

      echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width:100%;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
  }

}

class EcwidVCategoriesWidget extends WP_Widget {

    function EcwidVCategoriesWidget() {
    $widget_ops = array('classname' => 'widget_ecwid_vcategories', 'description' => __( "Vertical menu of categories") );
    $this->WP_Widget('ecwidvcategories', __('Ecwid Vertical Categories'), $widget_ops);
    }

    function widget($args, $instance) {
      extract($args);
      $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

      echo $before_widget;

      if ( $title )
      echo $before_title . $title . $after_title;

        $store_id = get_ecwid_store_id();
        $ecwid_protocol = get_ecwid_protocol();
        echo "<div><script type=\"text/javascript\" src=\"$ecwid_protocol://" . APP_ECWID_COM . "/script.js?$store_id\"></script>";
	$ecwid_page_id = get_option("ecwid_store_page_id");
        $page_url = get_page_link($ecwid_page_id);
                $_tmp_page = get_page($ecwid_page_id);
                if (!empty($page_url) && $_tmp_page != null)
		echo "<script>var ecwid_ProductBrowserURL = \"$page_url\";</script>";
      echo <<<EOT
	<script type="text/javascript"> xVCategories("style="); </script>
	      </div>
EOT;
      
echo $after_widget;
  }

    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['title'] = strip_tags(stripslashes($new_instance['title']));

    return $instance;
  }

    function form($instance){
      $instance = wp_parse_args( (array) $instance, array('title'=>'') );

      $title = htmlspecialchars($instance['title']);

      echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width:100%;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
  }

}


function ecwid_sidebar_widgets_init() {
	register_widget('EcwidMinicartWidget');
	register_widget('EcwidSearchWidget');
	register_widget('EcwidVCategoriesWidget');
}
add_action('widgets_init', 'ecwid_sidebar_widgets_init');

?>
