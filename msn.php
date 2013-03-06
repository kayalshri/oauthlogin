<?php
	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/msn.php
	@document	:	http://ngiriraj.com/work/hotmail-connect-by-using-oauth/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/
	
	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	
	/* CONFIGURE */
	$client->server = 'Microsoft';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/msn.php';

	$client->client_id = '00000000440E7C65'; $application_line = __LINE__;
	$client->client_secret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Invalid clientId or clientSecret!');
		
	/* scope	 */
	$client->scope = 'wl.basic wl.emails wl.birthday wl.skydrive wl.photos';
	
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
					'https://apis.live.net/v5.0/me',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"Me");

					$success = $client->CallAPI(
					'https://apis.live.net/v5.0/me/contacts',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"Hotmail contacts");

					/*
					$success = $client->CallAPI(
					'https://apis.live.net/v5.0/me/friends',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"Friends");
					
					
					$success = $client->CallAPI(
					'https://apis.live.net/v5.0/242d64016f5a7522/friends',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"242d64016f5a7522 Friends");
					
					
					$success = $client->CallAPI(
					'https://apis.live.net/v5.0/me/contacts',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"contacts");
					
										$success = $client->CallAPI(
					'https://apis.live.net/v5.0/me/contacts?limit=2',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"contacts limit");

					
					
					$success = $client->CallAPI(
					'https://apis.live.net/v5.0/me/albums',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printMSNdata($user,"albums");
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
		#		header("location: success.php");

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