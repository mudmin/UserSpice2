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
//Get token param
if(isset($_GET["token"]))
{
	$token = $_GET["token"];
	if(!isset($token))
	{
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	}
	else if(!validateActivationToken($token)) //Check for a valid token. Must exist and active must be = 0
	{
		$errors[] = lang("ACCOUNT_TOKEN_NOT_FOUND");
	}
	else
	{
		//Activate the users account
		if(!setUserActive($token))
		{
			$errors[] = lang("SQL_ERROR");
		}
	}
}
else
{
	$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
}

if(count($errors) == 0) {
	$successes[] = lang("ACCOUNT_ACTIVATION_COMPLETE");
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
<h2>Activate Account</h2>

<?php
echo resultBlock($errors,$successes);
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
