<!DOCTYPE html>
<html lang="en">
<head>
  <?php require('controllers/APIQuery.php'); ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>API Dump with Pagination</title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/JSONDump.css" rel="stylesheet">
</head>

<body>

  <div class="form-container">
    <h3>Enter URL</h3>
    <form method="POST" action="controllers\APIQuery.php">
      <div class="row">
        <input type="text" name="api-url" id="api-url" value="https://api.hubapi.com/deals/v1/deal/paged?hapikey=b7e10548-e390-44cf-84bd-554da46342d7&limit=10&properties=dealname&propertiesWithHistory=dealstage" required>
      </div>
      <div class="row">
        <input class="btn btn-info" type="submit" name="submit" value="Submit">
      </div>
    </form>
  </div>

  <br>
  <div class="form-container">
    <h4>Show all keys?</h4>
    <form method="POST" action="controllers\APIQuery.php">
      <div class="row">
        <input class="btn btn-info" type="submit" name="showKeys" value="Show Keys">
      </div>
    </form>
  </div>

  <!-- Page Content -->
  <div class="container">
    <!-- Page Heading -->
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Results
          <small>pagination currently broken.</small>
        </h1>
      </div>
    </div>

    <?php
    if (isset($_SESSION['deals'])){
      echo '<div class="centered-rows">';
      echo '<div class="row">';
      for ($i = 0; $i < count($_SESSION['deals']); $i++) {
        $deal = $_SESSION['deals'][$i];
        echo '<div class="col col-md-3 col-sm-6 col-xs-12 result-item"><p>'.$deal->id.'</p>'
        .'<p>'.$deal->name.'</p>'.'<p>'.$deal->time.'</p></div>';
        if (($i+1) % 4 == 0) {
          echo '</div><div class="row">';
        }
      }
      echo '</div></div>';
    }
    ?>



    <hr>

    <!-- Pagination -->
    <div class="row text-center">
      <div class="col-lg-12">
        <ul class="pagination">
          <li>
            <a href="#">&laquo;</a>
          </li>
          <li class="active">
            <a href="#">1</a>
          </li>
          <li>
            <a href="#">&raquo;</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- jQuery -->
  <script src="js/jquery.js"></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/JSONDump.js"></script>
</body>

</html>
