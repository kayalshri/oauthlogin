<?php

	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/foursquare.php
	@document	:	http://ngiriraj.com/work/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/

	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	
	/* CONFIGURE */
	$client->server = 'Foursquare';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/foursquare.php';

	$client->client_id = 'CVH2LUFNUVAYQVPYCECHZ0FGOKEQHLKIRJBGHBPQVV0T1ZX1'; 
	$application_line = __LINE__;
	$client->client_secret = 'xxxxxxxxxxxxxxxxxxxxxxxx';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Invalid clientId or clientSecret!');
		
	/* API permissions	 */
	$client->scope = '';
	
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://api.foursquare.com/v2/users/self?oauth_token='.$client->access_token,
					'GET', array(), array('FailOnAccessError'=>true), $user);					
					printMSNdata($user,"FS Me");
					

			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
		session_start();
		$_SESSION['userdata']=$user;
	#		header("location: home.php");

	}
	else
	{
      echo 'Error:'.HtmlSpecialChars($client->error); 
	}


function printMSNdata($data,$topic){
		echo "<h1>$topic</h1><pre>";
		print_r($data);
		echo "</pre><br>";
}
?>