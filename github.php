<?php

	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/github.php
	@document	:	http://ngiriraj.com/work/github-connect-by-using-oauth-in-php/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/
	
	require('lib/http.php');
	require('lib/oauth_client.php');
  
	$client = new oauth_client_class;
	
	/* CONFIGURE */
	$client->server = 'github';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/github.php';

	$client->client_id = '72ba7b34f12d6aaefda9'; $application_line = __LINE__;
	$client->client_secret = 'xxxxxxxxxxxxxxxxxxxx';

	
	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Invalid clientId or clientSecret!');

	/* SCOPE */
	$client->scope = 'user';
	
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
					'https://api.github.com/user',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printData($user,"Github My profile details");					
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
		/* you can redirect here */
	}
	else
	{
      echo 'Error:'.HtmlSpecialChars($client->error); 
	}

/* Output */
function printData($data,$topic){
		echo "<h1>$topic</h1><pre>";
		print_r($data);
		echo "</pre><br>";
}
?>