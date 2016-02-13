<?php
/*
UserSpice 2.5.5
by Dan Hoover at http://UserSpice.com

based on
UserCake Version: 2.0.2


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

<!-- If you are going to include the sidebar, do it here -->
<?php require_once("models/left-nav.php"); ?>
</div>
<!-- /.navbar-collapse -->
</nav>
<!-- PHP GOES HERE -->
<?php
//Forms posted
if(!empty($_POST))
{
  $deletions = $_POST['delete'];
  if ($deletion_count = deleteUsers($deletions)){
    $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
  }
  else {
    $errors[] = lang("SQL_ERROR");
  }
}

$userData = fetchAllUsers(); //Fetch information for all users
?>






<div id="page-wrapper">
  <!-- Main jumbotron for a primary marketing message or call to action -->

  <!-- <div class="jumbotron">
  <div class="container">
  <h1>Jumbotron!!!</h1>
  <p>This is a great area to highlight something.</p>
  <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
</div>
</div> -->

<div class="container-fluid">

  <!-- Page Heading -->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        Admin Users
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php
      echo resultBlock($errors,$successes);

      echo "
      <form name='adminUsers' action='".$_SERVER['PHP_SELF']."' method='post'>
      <table class='table table-hover'>
      <tr>
      <th>Delete</th><th>Username</th><th>Display Name</th><th>Title</th><th>Last Sign In</th>
      </tr>";

      //Cycle through users
      foreach ($userData as $v1) {
        echo "
        <tr>
        <td><input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'></td>
        <td><a href='admin_user.php?id=".$v1['id']."'>".$v1['user_name']."</a></td>
        <td>".$v1['display_name']."</td>
        <td>".$v1['title']."</td>
        <td>
        ";

        //Interprety last login
        if ($v1['last_sign_in_stamp'] == '0'){
          echo "Never";
        }
        else {
          echo date("j M, Y", $v1['last_sign_in_stamp']);
        }
        echo "
        </td>
        </tr>";
      }

      echo "
      </table>
      <input class='btn btn-primary' type='submit' name='Submit' value='Delete' />
      </form>
      ";
      ?>






    </div>
  </div>
  <!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<!-- footer -->
<?php require_once("models/footer.php"); ?>
