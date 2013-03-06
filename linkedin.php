<?php
	/* 
	@date 		:	Feb 12, 2013
	@demourl	:	http://ngiriraj.com/socialMedia/linkedin.php
	@document	:	http://ngiriraj.com/work/linkedin-connect-by-using-oauth-in-php/
	@ref		: 	@(#) $Id: oauth_client.php,v 1.46 2013/01/10 10:11:33 mlemos Exp $
	*/

	require('lib/http.php');
	require('lib/oauth_client.php');
    
	$client = new oauth_client_class;
	
	/* CONFIGURE */
	$client->server = 'LinkedIn';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/linkedin.php';

	$client->client_id = 'ej2ast2u49vo'; $application_line = __LINE__;
	$client->client_secret = 'xxxxxxxxxxxxxxxxxxx';

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		die('Invalid clientId or clientSecret!');
		
	/* SCOPE */
	$client->scope = 'r_fullprofile r_emailaddress';
	
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
					'http://api.linkedin.com/v1/people/~?format=json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printData($user,"Linkedin Me");
					
					$success = $client->CallAPI(
					'http://api.linkedin.com/v1/people/~:(id,first_name,date-of-birth,picture-url,email-address,public-profile-url)?format=json',
					'GET', array(), array('FailOnAccessError'=>true), $user);
					printData($user,"Linkedin Me (select field)");				
				
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