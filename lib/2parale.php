<?php
/* ================================
   2Parale.ro API 
   ver. 0.1.5
   http://comunitate.2parale.ro/api
   ================================ */

ini_set('include_path', ini_get('include_path') . ':' . dirname(__FILE__));

require_once 'HTTP/Request.php';
require_once 'XML/Serializer.php';
require_once 'XML/Unserializer.php';

class DouaParale {
	
	var $user;
	var $pass;
	
	function DouaParale($user, $pass, $host='http://api.2parale.ro') {
		$this->user = $user;
		$this->pass = $pass;
                $this->host = $host;
	}

        /*===========*/
        /* Campaigns */
        /*===========*/

        /* List campaigns. Displays the first 6 entries by default. */
        function campaigns_list($category_id=null, $page=1, $perpage=6) {
                $request['category_id'] = $category_id;
                $request['page']        = $page;
                $request['perpage']     = $perpage; 
         
                return $this->hook("/campaigns.xml", "campaign", $request, 'get');
        }

        /* Search for campaigns */
        function campaigns_search($search, $page=1, $perpage=6) {
		$request['page']    = $page;
                $request['perpage'] = $perpage;
                $request['search']  = $search;
               
                return $this->hook("/campaigns/search.xml", "campaign", $request, 'get');
        }

        /* Display public information about a campaign */
        function campaign_show($campaign_id) {
                return $this->hook("/campaigns/{$campaign_id}.xml", "campaign");
        }

        /* Affiliates: List campaigns which have the logged in user accepted */
        function campaigns_listforaffiliate() {
                return $this->hook("/campaigns/listforaffiliate.xml", "campaign");
        }

        /* Merchants: List all campaigns created by the logged in user */
        function campaigns_listforowner() {
                return $this->hook("/campaigns/listforowner.xml", "campaign");
        }

        /* Merchants: Display complete information about a campaign (only available to owner) */
        function campaign_showforowner($campaign_id) {
                return $this->hook("/campaigns/{$campaign_id}/showforowner.xml", "campaign");
        }
         
        /* Merchants: Update a campaign */
        function campaign_update($campaign_id, $campaign) {
                $request['campaign'] = $campaign;
                return $this->hook("/campaigns/{$campaign_id}.xml", "campaign", $request, 'put');
        }
      
        /*============*/
        /* Affiliates */
        /*============*/

        /* Search for affiliates */
        function affiliates_search($search, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;
                $request['search']  = $search;

                return $this->hook("/affiliates/search", "user", $request, 'get');
        }

        /* Merchants: List affiliates approved in campaigns */
	function affiliates_listformerchant($campaign_id=null) {
		$request['campaign_id'] = $campaign_id;
                return $this->hook("/affiliates/listformerchant", "user", $request, 'get');
        } 
       
        /*=============*/
        /* Commissions */
        /*=============*/
  
        /* Search for commissions.  Month: 01 to 12; Year: 20xx. Status: accepted, pending or rejected. null if empty search.*/
        function commissions_search($campaign_id, $month, $year, $search_name, $search_amount, $search_status, $search_transaction_id, $page=1, $perpage=6) {
                $request['campaign_id'] = $campaign_id;
                $request['month']       = $month;
                $request['year']        = $year;

                $request['search_name']           = $search_name;
                $request['search_amount']         = $search_amount;
                $request['search_status']         = $search_status;
                $request['search_transaction_id'] = $search_transaction_id;

                $request['page']    = $page;
                $request['perpage'] = $perpage;

                return $this->hook("/commissions/search.xml", "commission", $request, 'get');
        }

        /* Merchants: List commissions on campaigns. Month: 01 to 12; Year: 20xx. */
        function commissions_listformerchant($campaign_id, $month, $year) {
                $request['campaign_id'] = $campaign_id;
		$request['month']       = $month;
                $request['year']        = $year;

                return $this->hook("/commissions/listformerchant.xml", "campaign", $request, 'get');
        }

        /* Affiliates: List commissions on campaigns. Month: 01 to 12; Year: 20xx. */
        function commissions_listforaffiliate($campaign_id, $month, $year) {
                $request['campaign_id'] = $campaign_id;
                $request['month']       = $month;
                $request['year']        = $year;

                return $this->hook("/commissions/listforaffiliate.xml", "commission", $request, 'get');
        }

	/* Merchant Campaign Owner or Affiliate Commission Owner: Show information about a commission */
        function commission_show($commission_id) {
                return $this->hook("/commissions/{$commission_id}.xml", "commission");
        }

        /* Merchant: Update a commission */
        function commission_update($commission_id, $commission) {
                $request['commission'] = $commission;
                return $this->hook("/commissions/{$commission_id}.xml", "commission", $request, 'put');
        }

        /*=======*/
        /* Sites */
        /*=======*/

        /* List sites. Displays the first 6 entries by default. */
        function sites_list($category_id=null, $page=1, $perpage=6) {
                $request['category_id'] = $category_id;
                $request['page']        = $page;
                $request['perpage']     = $perpage;

                return $this->hook("/sites.xml", "site", $request);
        }

        /* Display information about a site */
        function site_show($site_id) {
                return $this->hook("/sites/{$site_id}.xml", "site");
        }

        /* Search for sites */
        function sites_search($search, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;
                $request['search']  = $search;

                return $this->hook("/sites/search.xml", "site", $request, 'get');
        }

        /* Affiliates: List all sites created by the logged in user */
        function sites_listforowner() {
                return $this->hook("/sites/listforowner.xml", "site");
        }

        /* Affiliates: Update a site */
        function site_update($site_id, $site) {
                $request['site'] = $site;
                return $this->hook("/sites/{$site_id}.xml", "site", $request, 'put');
        }


        /* Affiliates: Destroy a site */
        function site_destroy($site_id) {
                return $this->hook("/sites/{$site_id}.xml", "site", $request, 'delete');
        }

        /*============*/
        /* Text Links */
        /*============*/

        /* List text links from a campaign. Displays the first 6 entries by default. */
        function txtlinks_list($campaign_id, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;

                return $this->hook("/campaigns/{$campaign_id}/txtlinks.xml", "txtlink", $request, 'get');
        }

        /* Display information about a text link */
        function txtlink_show($campaign_id, $txtlink_id) {
                return $this->hook("/campaigns/{$campaign_id}/txtlinks/{$txtlink_id}.xml", "txtlink");
        }

        /* Search for text links in a campaign */
        function txtlinks_search($campaign_id, $search, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;
                $request['search']  = $search;

                return $this->hook("/campaigns/{$campaign_id}/txtlinks/search.xml", "txtlink", $request, 'get');
        }

        /* 
           Merchants: Create Text Link. 
           Txtlink must look like: array("title" => "title", "url" => "url", "help" => "help");  where "help" is optional
        */
        function txtlink_create($campaign_id, $txtlink) {
		$request['txtlink'] = $txtlink;

                return $this->hook("/campaigns/{$campaign_id}/txtlinks.xml", "txtlink", $request, 'post');
        }

        /* Merchants: Update a text link */
        function txtlink_update($campaign_id, $txtlink_id, $txtlink) {
                $request['txtlink'] = $txtlink;
                return $this->hook("/campaigns/{$campaign_id}/txtlinks/{$txtlink_id}.xml", "txtlink", $request, 'put');
        }

        /* Merchants: Destroy a text link */
        function txtlink_destroy($campaign_id, $txtlink_id) {
                return $this->hook("/campaigns/{$campaign_id}/txtlinks/{$txtlink_id}.xml", "txtlink", null, 'delete');
        }

        /*============*/
        /* Text Ads */
        /*============*/

        /* List text ads from a campaign. Displays the first 6 entries by default. */
        function txtads_list($campaign_id, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;

                return $this->hook("/campaigns/{$campaign_id}/txtads.xml", "txtad", $request, 'get');
        }

        /* Display information about a text ad */
        function txtad_show($campaign_id, $txtad_id) {
                return $this->hook("/campaigns/{$campaign_id}/txtads/{$txtad_id}.xml", "txtad");
        }

        /* Search for text ads in a campaign */
        function txtads_search($campaign_id, $search, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;
                $request['search']  = $search;

                return $this->hook("/campaigns/{$campaign_id}/txtads/search.xml", "txtad", $request, 'get');
        }

        /* 
           Merchants: Create Text Ad. 
           Txtad must look like: array("title" => "title", "content" => "content", "url" => "url", "help" => "help");  where "help" is optional
        */
        function txtad_create($campaign_id, $txtad) {
                $request['txtad'] = $txtad;
        
                return $this->hook("/campaigns/{$campaign_id}/txtads.xml", "txtad", $request, 'post');
        }


        /* Merchants: Update a text ad */
        function txtad_update($campaign_id, $txtad_id, $txtad) {
                $request['txtad'] = $txtad;
                return $this->hook("/campaigns/{$campaign_id}/txtads/{$txtad_id}.xml", "txtad", $request, 'put');
        }

        /* Merchants: Destroy a text ad */
        function txtad_destroy($campaign_id, $txtad_id) {
                return $this->hook("/campaigns/{$campaign_id}/txtads/{$txtad_id}.xml", "txtad", null, 'delete');
        }

        /*=========*/
        /* Banners */
        /*=========*/

        /* List banners from a campaign. Displays the first 6 entries by default. */
        function banners_list($campaign_id, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;

                return $this->hook("/campaigns/{$campaign_id}/banners.xml", "banner", $request, 'get');
        }

        /* Display information about a banner */
        function banner_show($campaign_id, $banner_id) {
                return $this->hook("/campaigns/{$campaign_id}/banners/{$banner_id}.xml", "banner");
        }

        /* Search for banners in a campaign */
        function banners_search($campaign_id, $search, $page=1, $perpage=6) {
                $request['page']    = $page;
                $request['perpage'] = $perpage;
                $request['search']  = $search;

                return $this->hook("/campaigns/{$campaign_id}/banners/search.xml", "banner", $request, 'get');
        }

        /* Merchants: Update a banner */
        function banner_update($campaign_id, $banner_id, $banner) {
                $request['banner'] = $banner;
                return $this->hook("/campaigns/{$campaign_id}/banners/{$banner_id}.xml", "banner", $request, 'put');
        }

        /* Merchants: Destroy a banner */
        function banner_destroy($campaign_id, $banner_id) {
                return $this->hook("/campaigns/{$campaign_id}/banners/{$banner_id}.xml", "banner", null, 'delete');
        }

        /*===============*/
        /* Widget Stores */
        /*===============*/

        /* List Widget Stores from a Campaign */
        function widget_stores_list($campaign_id) {
                $request['campaign_id'] = $campaign_id;

                return $this->hook("/widget_stores.xml", "widget-store", $request);
        }

        /* Show a WidgetStore */
        function widget_store_show($widget_store_id) {
                return $this->hook("/widget_stores/{$widget_store_id}.xml", "widget-store");
        }

        /* Show Products from a WidgetStore */
        function widget_store_showitems($widget_store_id, $category=null, $page=1, $perpage=6) {
                $request['category'] = $category;
                $request['page']     = $page;
                $request['perpage']  = $perpage;

                return $this->hook("/widget_stores/{$widget_store_id}/showitems.xml", "widget-store-data", $request);
        }

        /* Show a Product from a WidgetStore */
        function widget_store_showitem($widget_store_id, $product_id) {
                $request['product_id'] = $product_id;

                return $this->hook("/widget_stores/{$widget_store_id}/showitem.xml", "widget-store-data", $request);
        }


        /* Search for Products in a WidgetStore */
        function widget_store_products_search($campaign_id, $search, $widget_store_id='all', $category=null, $page=1, $perpage=6) {
                $request['page']        = $page;
                $request['perpage']     = $perpage;
                $request['search']      = $search;
                $request['category']    = $category;
                $request['campaign_id'] = $campaign_id;
                
                if (!$widget_store_id)
                  $widget_store_id = 'all';

                return $this->hook("/widget_stores/{$widget_store_id}/searchpr.xml", "widget-store-data", $request, 'get');
        }

        /* Merchants: Update a WidgetStore */
        function widget_store_update($widget_store_id, $widget_store) {
                $request['widget_store'] = $widget_store;
                return $this->hook("/widget_stores/{$widget_store_id}.xml", "widget-store", $request, 'put');
        }

        /* Merchants: Destroy a WidgetStore */
        function widget_store_destroy($widget_store_id) {
                return $this->hook("/widget_stores/{$widget_store_id}.xml", "widget-store", null, 'delete');
        }

        /* 
           Merchants: Create a WidgetStoreProduct. 
           WidgetStoreProduct must look like: 
              array("title" => "title", "description" => "desc", "caption" => "caption", "price" => "price(integer in RON)", 
                    "promoted" => "promoted (0 or 1)", "category" => "category", "subcategory" => "subcategory",  "url" => "url", 
                    "image_url" => "url to image location", "prid" => "product id");
        */
        function widget_store_createitem($widget_store_id, $product) {
                $request['product'] = $product;

                return $this->hook("/widget_stores/{$widget_store_id}/createitem.xml", "widget-store-data", $request, 'post');
        }

        /* Merchants: Update a product */
        function widget_store_updateitem($widget_store_id, $product_id, $product) {
                $request['product'] = $product;
                $request['product_id']   = $product_id;

                return $this->hook("/widget_stores/{$widget_store_id}/updateitem.xml", "widget-store-data", $request, 'put');
        }

        /* Merchants: Destroy a product */
        function widget_store_destroyitem($widget_store_id, $product_id) {
        	$request['pr_id'] = $product_id;

                return $this->hook("/widget_stores/{$widget_store_id}/destroyitem.xml", "widget-store-data", $request, 'delete');
        }

        /*=====================*/
        /* Affiliate Ad Groups */
        /*=====================*/
        
        /* Affiliates: List Ad Groups */
        function ad_groups_list() {
                return $this->hook("/ad_groups.xml", "ad_group", null, "get");
        }

        /* Affiliates: Display information about an Ad Group */
        function ad_group_show($ad_group_id) {
                return $this->hook("/ad_groups/{$ad_group_id}.xml", "ad_group", null, "get");
        }

        /* Affiliates: Destroy an Ad Group */
        function ad_group_destroy($ad_group_id) {
                return $this->hook("/ad_groups/{$ad_group_id}.xml", "ad_group", null, "delete");
        }

	/* Affiliates: Delete an Tool from a Group. $tooltype is one of 'txtlink', 'txtad' or 'banner'. */
        function ad_group_destroyitem($ad_group_id, $tool_type, $tool_id) {
                $request['tool_type'] = $tool_type;
                $request['tool_id']   = $tool_id;

                return $this->hook("/ad_groups/{$ad_group_id}/destroyitem.xml", "ad_group", $request, "delete");
        }

        /*==========*/
        /* Messages */
        /*==========*/

        /* List received messages. Displays the first 6 entries by default. */
        function received_messages_list($page=1, $perpage=6) {
                $request['page']      = $page;
                $request['perpage']   = $perpage;

                return $this->hook("/messages.xml", "message", null, "get");
        }

        /* List sent messages. Displays the first 6 entries by default. */
        function sent_messages_list($page=1, $perpage=6) {
                $request['page']      = $page;
                $request['perpage']   = $perpage;

                return $this->hook("/messages/sent.xml", "message", null, "get");
        }

        /* Display information about a message */
        function message_show($message_id) {
                return $this->hook("/messages/{$message_id}.xml", "message");
        }

        /* Destroy a message */
        function message_destroy($message_id) {
                return $this->hook("/messages/{$message_id}.xml", "message", null, 'delete');
        }


        /*===========================*/
        /* Actually process the data */
        /*===========================*/
	
	function hook($url,$expected,$send = false, $method = null) {
		$returned = $this->unserialize($this->request($url,$send, $method));
		$placement = $expected;
		if (isset($returned->{$expected})) {
			$this->{$placement} = $returned->{$expected};	
			return $returned->{$expected};
		} else {
			$this->{$placement} = $returned;
			return $returned;
		}
	}
	
	function request($url, $params = false, $method = null) {
		//do the connect to a server thing
		$req =& new HTTP_Request($this->host . $url);
		//authorize
		$req->setBasicAuth($this->user, $this->pass);
		//set the headers
		$req->addHeader("Accept", "application/xml");
		$req->addHeader("Content-Type", "application/xml");
		//if were sending stuff
		if ($params) {
			//serialize the data
			$xml = $this->serialize($params);
			//print_r($xml);
			($xml)?$req->setBody($xml):false;
		}

		// set method
		if ($method == 'post') {
			$req->setMethod(HTTP_REQUEST_METHOD_POST);
                } else if ($method == 'put') {
                        $req->setMethod(HTTP_REQUEST_METHOD_PUT);
                } else if ($method == 'delete') {
                        $req->setMethod(HTTP_REQUEST_METHOD_DELETE);
		}
		$response = $req->sendRequest();
		//print_r($req->getResponseHeader());
		//echo $req->getResponseCode() .	"\n";
		
		if (PEAR::isError($response)) {
		    return $response->getMessage();
		} else {
			//print_r($req->getResponseBody());
		    return $req->getResponseBody();
		}
	}
	
	function serialize($data) {
		$options = array(	XML_SERIALIZER_OPTION_MODE => XML_SERIALIZER_MODE_SIMPLEXML,
                                                        XML_SERIALIZER_OPTION_ROOT_NAME   => 'request',
							XML_SERIALIZER_OPTION_INDENT => '  ');
		$serializer = new XML_Serializer($options);
		$result = $serializer->serialize($data);
		return ($result)?$serializer->getSerializedData():false;
	}
	
	function unserialize($xml) {
		$options = array (XML_UNSERIALIZER_OPTION_COMPLEXTYPE => 'object');
		$unserializer = &new XML_Unserializer($options);
		$status = $unserializer->unserialize($xml);    
	    $data = (PEAR::isError($status))?$status->getMessage():$unserializer->getUnserializedData();
		return $data;
	}
}


?>
