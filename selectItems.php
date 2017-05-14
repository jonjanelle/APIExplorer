<!DOCTYPE html>
<html lang="en">
<head>
  <?php require('controllers/APIQuery.php'); ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>JSON Public API Structure Inspector</title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/JSONDump.css" rel="stylesheet">
</head>

<body>
  <div class="form-container">
    <h4>Test Key Value Chain Query</h4>
    <form method="POST" action="controllers\APIQuery.php">
      <div class="row">
        <input class="btn btn-info" type="submit" name="getKeyValPairs" value="Test Key Value Chain">
      </div>
    </form>
  </div>
</body>
</html>
