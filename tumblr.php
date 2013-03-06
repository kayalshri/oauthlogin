<?php

	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/tumblr.php
	@document	:	http://ngiriraj.com/work/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/

	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	
	/* CONFIGURE */
	$client->server = 'Tumblr';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/tumblr.php';

	$client->client_id = 'YQT4RTAqukuWXqqg95vqebIwsUejKdZa4fzfHmQ930qautPais'; $application_line = __LINE__;
	$client->client_secret = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
				die('Invalid clientId or clientSecret!');
				
	/* SCOPE	 */
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
					'https://api.tumblr.com/v2/user/info',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printdata($user,"Tumblr Me");					

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


function printdata($data,$topic){
		echo "<h1>$topic</h1><pre>";
		print_r($data);
		echo "</pre><br>";
}
?>