<?php
require('Deal.php');
session_start();

function processForm() {
  $targetURL = $_POST['api-url'];
  $target="https://api.hubapi.com/deals/v1/deal/paged?hapikey=b7e10548-e390-44cf-84bd-554da46342d7&limit=10&properties=dealname&propertiesWithHistory=dealstage";
  $_SESSION['deals']=array();
  showAll($target, 0, 10);
  $_SESSION['fullRows']=intdiv(count($_SESSION['deals']),4);
  header('Location: ../');
}


//Query api recursively until hasMore attribute is false.
function showAll($targetURL, $depth, $depthLimit)
{
  $json = file_get_contents($targetURL);
  $obj = json_decode($json);

  //Create deal object for each result
  for ($i=0; $i<count($obj->deals); $i++) {
      $currentDeal = $obj->deals[$i];
      $properties = $currentDeal->properties;
      $id = $currentDeal->portalId;
      $name = $properties->dealname->value;
      $timeStamp = $properties->dealname->timestamp;
      array_push($_SESSION['deals'],new Deal($id, $name, $timeStamp));
  }

  if ($obj->hasMore && $depth<$depthLimit) {
    $offsetPos=strpos($targetURL,"offset=");
    if ($offsetPos!=False) { //Remove old offset position if needed
      $targetURL=substr($targetURL,0,$offsetPos-1);
    }
    $targetURL.="&offset=".$obj->offset; //Add new offset position to url
    showAll($targetURL, $depth+1, $depthLimit);//do it all again.
  }
}

//Process form and clear previous data if here after submit click
if(isset($_POST['submit']))
{
  processForm();
}
