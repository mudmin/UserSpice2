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
$pages = getPageFiles(); //Retrieve list of pages in root usercake folder
$dbpages = fetchAllPages(); //Retrieve list of pages in pages table
$creations = array();
$deletions = array();

//Check if any pages exist which are not in DB
foreach ($pages as $page){
  if(!isset($dbpages[$page])){
    $creations[] = $page;
  }
}

//Enter new pages in DB if found
if (count($creations) > 0) {
  createPages($creations)	;
}

if (count($dbpages) > 0){
  //Check if DB contains pages that don't exist
  foreach ($dbpages as $page){
    if(!isset($pages[$page['page']])){
      $deletions[] = $page['id'];
    }
  }
}

//Delete pages from DB if not found
if (count($deletions) > 0) {
  deletePages($deletions);
}

//Update DB pages
$dbpages = fetchAllPages();
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
        Admin Pages
      </h1>
      <!-- CONTENT GOES HERE -->

      <?php
      echo "
      <div id='main'>
      <table class='table'>
      <tr><th>Id</th><th>Page</th><th>Access</th></tr>";

      //Display list of pages
      foreach ($dbpages as $page){
        echo "
        <tr>
        <td>
        ".$page['id']."
        </td>
        <td>
        <a href ='admin_page.php?id=".$page['id']."'>".$page['page']."</a>
        </td>
        <td>";

        //Show public/private setting of page
        if($page['private'] == 0){
          echo "Public";
        }
        else {
          echo "Private";
        }

        echo "
        </td>
        </tr>";
      }

      ?>
    </table>






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
