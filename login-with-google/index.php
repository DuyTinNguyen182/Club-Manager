<?php
session_start();
$google_client_id 		= '841226203945-7vmhke1k135jvlufuf85bgkgfmqtm9b0.apps.googleusercontent.com';
$google_client_secret 	= 'qUuA7fqYRqJ4suGDJARUGM9r';
$google_redirect_url 	= 'http://localhost/Duy/login-with-google/'; //path to your script
$google_developer_key 	= 'AIzaSyAg-vsIL0U7Ktu_Nkn5-4dVJoMHLxYK7lo';
$google_redirect_url1="http://localhost/Duy";
require_once 'Google/Google_Client.php';
require_once 'Google/contrib/Google_Oauth2Service.php';

$gClient = new Google_Client();
$gClient->setApplicationName('Login Demo');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);

$google_oauthV2 = new Google_Oauth2Service($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
 session_destroy();
unset($_SESSION['emailUser']);
unset($_SESSION['quyen']);

  $gClient->revokeToken();
  session_destroy();
  header('Location: ' . filter_var($google_redirect_url1, FILTER_SANITIZE_URL)); //redirect user back to page
}

//If code is empty, redirect user to google authentication page for code.
//Code is required to aquire Access Token from google
//Once we have access token, assign token to session variable
//and we can redirect user back to page and login.
if (isset($_GET['code'])) 
{ 
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	 //$user = $google_oauthV2->userinfo->get(); // 
	 //$email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL); // // 
	// $_SESSION["email"] = $email; //
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL)); 
	return;
}


if (isset($_SESSION['token'])) 
{ 
	$gClient->setAccessToken($_SESSION['token']);
}


if ($gClient->getAccessToken()) 
{
	  //For logged in user, get details from google using access token
	  $user 				= $google_oauthV2->userinfo->get();
	  
	  $user_id 				= $user['id'];
	  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	 
	  $profile_url 			= '';// filter_var($user['link'], FILTER_VALIDATE_URL);
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
	  $_SESSION["emailUser"] = $email; // cái này quan trọng
}
else {
	//For Guest user, get google login url
	$authUrl = $gClient->createAuthUrl();
}

if(isset($authUrl)) //user is not logged in, show login button
{
	header("Location: ".$authUrl);
} 
else // user logged in 
{	
	//list all user details
	//echo '<pre>'; 
	//print_r($user);
	//echo '</pre>';	
	//echo "<br> email: ". $_SESSION["emailUser"];
	echo "<script language=javascript>
	window.location='../index.php';
	</script>
	";
	//header("Location: http://drl.sit.tvu.edu.vn/index.php");
}
?>