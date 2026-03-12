<?php
	//-------------------------- You need to set these --------------------------//
	$your_api_key = '1ep1oGWT401360Tt8fsY'; //get this from your Sendy main settings
	$your_installation_url = 'http://mailhandler.com'; //Your Sendy installation (without the trailing slash)
	$list = 'E4JKyy2sWjArfT892w763PEIEQ'; //Can be retrieved from "View all lists" page
	//---------------------------------------------------------------------------//

	//Check subscriber count for the list
	$postdata = http_build_query(
	    array(
	    'api_key' => $your_api_key,
        'list_ids' => $list,
        'from_name' => 'Glenn Bennett',
        'from_email' => 'gbennett@tsgdev.com',
        'reply_to' => 'gbennett@tsgdev.com',
        'title' => "title",
        'subject' => 'subject',
        'plain_text' => 'plain_text',
        'html_text' => 'html_text',
        'track_opens' => 1,
        'track_clicks' => 1,
        'send_campaign' => 1,
//        'brand_id' => '1',
	    
	    )
	);
	$opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
	$context  = stream_context_create($opts);
	$result = file_get_contents($your_installation_url.'/api/campaigns/create.php', false, $context);

	//check result and redirect
	if($result)
		echo 'result: '.$result;
	else
		echo 'Failed.';
?>