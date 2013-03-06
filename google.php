<?php

	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/google.php
	@document	:	http://ngiriraj.com/work/google-connect-by-using-oauth-in-php/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/
	
	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	$client->server = 'Google';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/google.php';

	$client->client_id = '730362277469-tbeqm6l332n1al4pnfdgb83786a6g3f2.apps.googleusercontent.com'; 
	$application_line = __LINE__;
	$client->client_secret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Invalid clientId or clientSecret!');

	/* SCOPE */
	$client->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me https://www.google.com/m8/feeds';
	
	
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
					'https://www.googleapis.com/oauth2/v1/userinfo',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printData($user,"Google Me");
					
					#$userid = $user->id;
					#$emailid = $user->email;
					#$profile_url = ($user->publicProfileUrl) ? ($user->publicProfileUrl) : "";
					#$img_url = ($user->pictureUrl) ? ($user->pictureUrl) : "";

					/*
					$success = $client->CallAPI(
					'https://www.googleapis.com/oauth2/v1/tokeninfo',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"Token Info");
					
					$success = $client->CallAPI(
					'https://www.google.com/m8/feeds/contacts/default/full?alt=json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"Google Contacts ");	
					
					*/

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
		/* REDIRECT */
		# header("location: success.php");
	}
	else
	{
      echo 'Error:'.HtmlSpecialChars($client->error); 
	}


function printData($data,$topic){
		echo "<h1>$topic</h1><pre>";
		print_r($data);
		echo "</pre><br>";
}
?>