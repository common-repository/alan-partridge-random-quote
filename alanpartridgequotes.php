<?php
/**
 * @package Alan Partridge Quotes
 * @author Alex Payne
 * @version 0.1
 */
/*
Plugin Name: Alan Partridge Random Quote
Plugin URI: http://apps.facebook.com/alanpartridge/#
Description: Alan Partridge hits WordPress! To show a random quote on your site just put <code>&lt;?php alanpartridge_quote(); ?&gt;</code> in your template.
Author: Alex Payne
Version: 0.1
Author URI: http://www.moresense.co.uk/
*/

function alanpartridge_quote_get() {
	$path = 'http://www.moresense.co.uk/alanpartridge/';
	
	$request = '';
	$http_request  = "POST $path HTTP/1.0\r\n";
	$http_request .= "Host: ".$_SERVER['HTTP_HOST']."\r\n";
	$http_request .= "URI: ".$_SERVER['REQUEST_URI']."\r\n";
	$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=" . get_option('blog_charset') . "\r\n";
	$http_request .= "Content-Length: " . strlen($request) . "\r\n";
	$http_request .= "User-Agent: WordPress/$wp_version | AlanPartridgeQuotes/0.1\r\n";
	$http_request .= "\r\n";
	$http_request .= $request;
	
	
	$dns = (array)gethostbynamel( rtrim("www.moresense.co.uk", '.') . '.' );
	$http_host = $dns[0];

	$response = '';
	if( false != ( $fs = @fsockopen($http_host, 80, $errno, $errstr, 10) ) ) {
		fwrite($fs, $http_request);

		while ( !feof($fs) )
			$response .= fgets($fs, 1160); // One TCP-IP packet
		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);
		$response = $response[1];
	}
	
	return wptexturize($response);
}

function alanpartridge_quote() {
	$chosen = alanpartridge_quote_get();
	if($chosen>''){
		echo alanpartridge_quote_css().'<div id="alanpartridge_quote">'.$chosen."</div>";
	}
}

function alanpartridge_quote_css() {
	/* If you wish to add your own style information please enter it here */
	echo '<style type="text/css">
	#alanpartridge_quote {margin:5px 0px;}
	#alanpartridge_quote .face {margin:0px 6px 6px 0px;}
	</style>';
}
?>
