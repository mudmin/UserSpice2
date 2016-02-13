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
  //Delete permission levels
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
      $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
  }

  //Create new permission level
  if(!empty($_POST['newPermission'])) {
    $permission = trim($_POST['newPermission']);

    //Validate request
    if (permissionNameExists($permission)){
      $errors[] = lang("PERMISSION_NAME_IN_USE", array($permission));
    }
    elseif (minMaxRange(1, 50, $permission)){
      $errors[] = lang("PERMISSION_CHAR_LIMIT", array(1, 50));
    }
    else{
      if (createPermission($permission)) {
        $successes[] = lang("PERMISSION_CREATION_SUCCESSFUL", array($permission));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
  }
}

$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels
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
        Admin Permissions
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php

      echo resultBlock($errors,$successes);

      echo "
      <form name='adminPermissions' action='".$_SERVER['PHP_SELF']."' method='post'>
      <table class='table table-hover'>
      <tr>
      <th>Delete</th><th>Permission Name</th>
      </tr>";

      //List each permission level
      foreach ($permissionData as $v1) {
        echo "
        <tr>
        <td><input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'></td>
        <td><a href='admin_permission.php?id=".$v1['id']."'>".$v1['name']."</a></td>
        </tr>";
      }

      echo "
      </table>
      <p>
      <label>Permission Name:</label>
      <input type='text' name='newPermission' />
      </p>
      <input class='btn btn-primary' type='submit' name='Submit' value='Submit' />
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
