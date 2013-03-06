<?php

	/* 
	@date 		:	Mar 06, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/fitbit.php
	@document	:	http://ngiriraj.com/work/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/

	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	
	/* COFIGURE */
	$client->server = 'Fitbit';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/fitbit.php';

	$client->client_id = '7043d9b795544e31888a21c2488210a7'; 
	$application_line = __LINE__;
	$client->client_secret = 'xxxxxxxxxxxxxxxxxxxxxxxxx';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('invalid client id or secret key');

	/* API permissions */
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
					'http://api.fitbit.com/1/user/-/profile.json',
					'GET', array(), array('FailOnAccessError'=>true), $user);					
					printdata($user,"Fitbit My profile");
					

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