<?php

	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/yahoo.php
	@document	:	http://ngiriraj.com/work/yahoo-profile-connect-by-using-oauth-php/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/
	
	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	
	/* CONFIGURE */
	$client->server = 'Yahoo';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/yahoo.php';

	$client->client_id = 'dj0yJmk9a0ZpM3NhWW5HSWR0JmQ9WVdrOWQwOVVRWGxsTkhNbWNHbzlOVE00TnpJek9UWXkmcz1jb25zdW1lcnNlY3JldCZ4PTdh'; 
	$application_line = __LINE__;
	$client->client_secret = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXX';

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
					'http://social.yahooapis.com/v1/me/guid?format=json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printdata($user,"Yahoo");

					#$ud = $user->guid->value;
					#$userid = $user->profile->guid;
					#$username = $user->profile->nickname;
					#$emailid = $user->emailAddress;
					
					$success = $client->CallAPI(
					'http://social.yahooapis.com/v1/user/'.$ud.'/profile/tinyusercard?format=json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printdata($user,"Yahoo Tinyusercard ");
					
					
					/*
					$success = $client->CallAPI(
					#	'http://social.yahooapis.com/v1/user/DK6IFMIBNOBF46LGT6ZMSHNVQY/profile/usercard?format=json',
					#	'http://social.yahooapis.com/v1/user/DK6IFMIBNOBF46LGT6ZMSHNVQY/profile/idcard?format=json',
					'http://social.yahooapis.com/v1/user/DK6IFMIBNOBF46LGT6ZMSHNVQY/profile?format=json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printdata($user,"Yahoo Usercard ");
					
					$success = $client->CallAPI(
					'http://social.yahooapis.com/v1/user/DK6IFMIBNOBF46LGT6ZMSHNVQY/contacts?format=json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printdata($user,"Yahoo connections ");				
					
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