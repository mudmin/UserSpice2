<?php
/*
UserCake Responsive 2.5.0
by Dan Hoover

based on
UserCake Version: 2.0.2
http://usercake.com

UserCake created by: Adam Davis
UserCake V2.0 designed by: Jonathan Cassels

Please note that this version uses technology that some consider
to be outdated. This version is designed as a cosmetic upgrade for
users of 2.0.2 and as a path towards development of version 3.0 and beyond
*/
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<?php require_once("models/top-nav.php"); ?>

<?php
// Top Code Goes Here
//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

//Forms posted
if(!empty($_POST))
{
	$errors = array();
	$email = trim($_POST["email"]);
	$username = trim($_POST["username"]);
	$displayname = trim($_POST["displayname"]);
	$password = trim($_POST["password"]);
	$confirm_pass = trim($_POST["passwordc"]);
	$captcha = md5($_POST["captcha"]);


	if ($captcha != $_SESSION['captcha'])
	{
		$errors[] = lang("CAPTCHA_FAIL");
	}
	if(minMaxRange(5,25,$username))
	{
		$errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($username)){
		$errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
	}
	if(minMaxRange(5,25,$displayname))
	{
		$errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($displayname)){
		$errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
	}
	if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
	{
		$errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
	}
	else if($password != $confirm_pass)
	{
		$errors[] = lang("ACCOUNT_PASS_MISMATCH");
	}
	if(!isValidEmail($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	//End data validation
	if(count($errors) == 0)
	{
		//Construct a user object
		$user = new User($username,$displayname,$password,$email);

		//Checking this flag tells us whether there were any errors such as possible data duplication occured
		if(!$user->status)
		{
			if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
			if($user->displayname_taken) $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
			if($user->email_taken) 	  $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));
		}
		else
		{
			//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
			if(!$user->userCakeAddUser())
			{
				if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
				if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
			}
		}
	}
	if(count($errors) == 0) {
		$successes[] = $user->success;
	}
}

?>

<!-- Main jumbotron for a primary marketing message or call to action -->
<!-- <div class="jumbotron">
<div class="container">
<h1>Jumbotron!!!</h1>
<p>This is a great area to highlight something.</p>
<p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
</div>
</div> -->

<div class="container">
  <div class="row">
    <div class="col-md-12">
					<div class="col-md-3"><?php include("models/left-nav.php");  ?></div>
				<div class="col-md-8">

<!-- Main content goes here!           -->
<h2>Register</h2>
<?php
echo resultBlock($errors,$successes);

echo "
<div id='regbox'>
<form name='newUser' action='register.php' method='post'>

<p>
<label>User Name (No Spaces or Special Characters - Min 5 characters):</label>
<input class='form-control' type='text' name='username' />
</p>
<p>
<label>Display Name (No Spaces or Special Characters - Min 5 characters):</label>
<input class='form-control' type='text' name='displayname' />
</p>
<p>
<label>Password (Min 8 Characters):</label>
<input class='form-control' type='password' name='password' />
</p>
<p>
<label>Confirm Password:</label>
<input class='form-control' type='password' name='passwordc' />
</p>
<p>
<label>Email:</label>
<input class='form-control' type='text' name='email' />
</p>
<p>
<label>Security Code:</label>
<img src='models/captcha.php'>
</p>
<label>Enter Security Code:</label>
<input class='form-control' name='captcha' type='text'>
</p>
<label>&nbsp;<br>
<input class='btn btn-primary' type='submit' value='Register'/>
</p>

</form>
";
?>
</div>
</div>


    </div>
  </div>
</div>
</div>
</div>
</div>


<?php require_once("models/footer.php"); ?>

</body>
</html>
