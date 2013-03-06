<?php


	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/facebook.php
	@document	:	http://ngiriraj.com/work/facebook-connect-by-using-oauth-in-php/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/

	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	
	/* CONFIGURE */
	$client->server = 'Facebook';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/facebook.php';

	$client->client_id = '482962811753376'; $application_line = __LINE__;
	$client->client_secret = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Invalid clientId or clientSecret!');
		
	/* SCOPE */
	$client->scope = 'email,publish_stream,status_update,friends_online_presence,user_birthday,user_location,user_work_history';
	
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
					'https://graph.connect.facebook.com/me/',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printdata($user,"FB Me");
								
					#$userid = $user->id;
					#$username = $user->name;
					
					$success = $client->CallAPI(
					'https://graph.connect.facebook.com/me/friends',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printdata($user,"FB friends");				
					
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