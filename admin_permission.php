<?php
/*
UserSpice 2.5.6
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
$permissionId = $_GET['id'];

//Check if selected permission level exists
if(!permissionIdExists($permissionId)){
  header("Location: admin_permissions.php"); die();
}

$permissionDetails = fetchPermissionDetails($permissionId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){

  //Delete selected permission level
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
      $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }
  }
  else
  {
    //Update permission level name
    if($permissionDetails['name'] != $_POST['name']) {
      $permission = trim($_POST['name']);

      //Validate new name
      if (permissionNameExists($permission)){
        $errors[] = lang("ACCOUNT_PERMISSIONNAME_IN_USE", array($permission));
      }
      elseif (minMaxRange(1, 50, $permission)){
        $errors[] = lang("ACCOUNT_PERMISSION_CHAR_LIMIT", array(1, 50));
      }
      else {
        if (updatePermissionName($permissionId, $permission)){
          $successes[] = lang("PERMISSION_NAME_UPDATE", array($permission));
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
    }

    //Remove access to pages
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($permissionId, $remove)) {
        $successes[] = lang("PERMISSION_REMOVE_USERS", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Add access to pages
    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($permissionId, $add)) {
        $successes[] = lang("PERMISSION_ADD_USERS", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Remove access to pages
    if(!empty($_POST['removePage'])){
      $remove = $_POST['removePage'];
      if ($deletion_count = removePage($remove, $permissionId)) {
        $successes[] = lang("PERMISSION_REMOVE_PAGES", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    //Add access to pages
    if(!empty($_POST['addPage'])){
      $add = $_POST['addPage'];
      if ($addition_count = addPage($add, $permissionId)) {
        $successes[] = lang("PERMISSION_ADD_PAGES", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    $permissionDetails = fetchPermissionDetails($permissionId);
  }
}

$pagePermissions = fetchPermissionPages($permissionId); //Retrieve list of accessible pages
$permissionUsers = fetchPermissionUsers($permissionId); //Retrieve list of users with membership
$userData = fetchAllUsers(); //Fetch all users
$pageData = fetchAllPages(); //Fetch all pages

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
      <form name='adminPermission' action='".$_SERVER['PHP_SELF']."?id=".$permissionId."' method='post'>
      <table class='table'>
      <tr><td>
      <h3>Permission Information</h3>
      <div id='regbox'>
      <p>
      <label>ID:</label>
      ".$permissionDetails['id']."
      </p>
      <p>
      <label>Name:</label>
      <input type='text' name='name' value='".$permissionDetails['name']."' />
      </p>
      <label>Delete:</label>
      <input type='checkbox' name='delete[".$permissionDetails['id']."]' id='delete[".$permissionDetails['id']."]' value='".$permissionDetails['id']."'>
      </p>
      </div></td><td>
      <h3>Permission Membership</h3>
      <div id='regbox'>
      <p>
      Remove Members:";

      //List users with permission level
      foreach ($userData as $v1) {
        if(isset($permissionUsers[$v1['id']])){
          echo "<br><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['display_name'];
        }
      }

      echo"
      </p><p>Add Members:";

      //List users without permission level
      foreach ($userData as $v1) {
        if(!isset($permissionUsers[$v1['id']])){
          echo "<br><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['display_name'];
        }
      }

      echo"
      </p>
      </div>
      </td>
      <td>
      <h3>Permission Access</h3>
      <div id='regbox'>
      <p>
      Public Access:";

      //List public pages
      foreach ($pageData as $v1) {
        if($v1['private'] != 1){
          echo "<br>".$v1['page'];
        }
      }

      echo"
      </p>
      <p>
      Remove Access:";

      //List pages accessible to permission level
      foreach ($pageData as $v1) {
        if(isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
          echo "<br><input type='checkbox' name='removePage[".$v1['id']."]' id='removePage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page'];
        }
      }

      echo"
      </p><p>Add Access:";

      //List pages inaccessible to permission level
      foreach ($pageData as $v1) {
        if(!isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
          echo "<br><input type='checkbox' name='addPage[".$v1['id']."]' id='addPage[".$v1['id']."]' value='".$v1['id']."'> ".$v1['page'];
        }
      }

      echo"
      </p>
      </div>
      </td>
      </tr>
      </table>
      <p>
      <label>&nbsp;</label>
      <input class='btn btn-primary' type='submit' value='Update Permission' class='submit' /><a href='admin_permissions.php' class='btn btn-danger'>Return to Permissions</a>
      </p>
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
