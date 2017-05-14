<?php
require('Deal.php');
session_start();

$varPaths = [];

function processForm() {
  $targetURL = $_POST['api-url'];
  $_SESSION['deals']=array();
  showAll($targetURL, 0, 10);
  $_SESSION['fullRows']=intdiv(count($_SESSION['deals']),4);
  header('Location: ../');
}


//Query api recursively until hasMore attribute is false.
//or until depth limit exceeded
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
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
function getKeyValPairs()
{
  $target="https://api.hubapi.com/deals/v1/deal/paged?hapikey=b7e10548-e390-44cf-84bd-554da46342d7&limit=10&properties=dealname&propertiesWithHistory=dealstage";
  $json = file_get_contents($target);
  $obj = json_decode($json);
  //echo count($obj->{"deals"});
  //First value should be an array or object
  $keyPathSet = ["deals"=>"array", "NA"=>"object","dealId"=>"int","properties"=>"object", "dealname"=>"object", "value"=>"string","source"=>"string", "sourceId"=>"string", "timestamp"=>"string"];

  //reset($keyPathSet);
  //$first_key = key($keyPathSet);
  testHelper($obj, $keyPathSet);
}

//Attempt to go from keyPathSet to outputting correct fields
function testHelper($currentLevel, $keyPathSet) {
  //  echo "<br>".var_dump($keyPathSet)."<br>";
  if (count($keyPathSet)==0) { return; }

  foreach ($keyPathSet as $key=>$value) {
    if ($value == "array" && is_object($currentLevel)) { //Should cover deals=>array
      unset($keyPathSet[$key]);
      //echo "Moving from ".gettype($currentLevel)." to ".$value."<br>";
      //echo var_dump($keyPathSet)."<br>";
      testHelper($currentLevel->{$key},$keyPathSet);
      break;
    }

    else if (is_array($currentLevel) && $value=="object"){
      for ($i=0; $i<count($currentLevel); $i++){
        unset($keyPathSet[$key]);
        //echo "Moving from ".gettype($currentLevel)." to ".$value."<br>";
        //echo var_dump($keyPathSet)."<br>";
        testHelper($currentLevel[$i],$keyPathSet);
      }
      break;
    }

    else if (is_object($currentLevel)) {
      if ($value == "object") {
        unset($keyPathSet[$key]);
        //echo "Moving from ".gettype($currentLevel)." to ".$value."<br>";
        //echo var_dump($keyPathSet)."<br>";
        testHelper($currentLevel->$key, $keyPathSet);
        break;
      }

      else if ($value!="array") {
        echo $key.": ".$currentLevel->$key."<br>";
        unset($keyPathSet[$key]);
        if (count($keyPathSet)==0) { echo "<br><br>";}
      }
    }
  }
}
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
function showKeys() {
  //$target="https://api.hubapi.com/deals/v1/deal/paged?hapikey=b7e10548-e390-44cf-84bd-554da46342d7&limit=10&properties=dealname&propertiesWithHistory=dealstage";
  //$target = "http://api.pathofexile.com/public-stash-tabs";
  $target = $_POST['api-url'];
  $json = file_get_contents($target);
  $obj = json_decode($json);
  $foundKeys = [];
  showKeysHelper($obj, $foundKeys,[]);
  $_SESSION['fkeys']=$foundKeys;
  header('Location: ../');
  /*
  $pos = 0;
  foreach ($foundKeys as $key=>$value){
    $indent = "";
    foreach ($value[2] as $k2=>$v2) {
    if ($v2 == "object"||$v2 == "array"){
        if ($pos>0) {
          $indent.="&nbsp&nbsp";
        }
      }
    }
    $pos+=1;
    echo $indent.$key." (".$value[0]."), sample: ".$value[1]."<br>";
  }
  */
}

function showKeysHelper($data, &$foundKeys, $path)
{
  foreach ($data as $key=>$value){
    if (!array_key_exists($key, $foundKeys)){
      $path[$key]=gettype($value);
      if (gettype($value)!="array" && gettype($value)!="object"){
        $foundKeys[$key]=[gettype($value), $value, $path];
      } else {
        $foundKeys[$key]=[gettype($value),"N/A",$path];
      }
    }
    if (is_array($value) || is_object($value)) {
      showKeysHelper($value, $foundKeys, $path);
      break;
    }
  }
}
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

//Process form and clear previous data if here after submit click
if(isset($_POST['submit']))
{
  processForm();
}

//Process form and clear previous data if here after submit click
if(isset($_POST['showKeys']))
{
  showKeys();
}

if(isset($_POST['getKeyValPairs'])){
  getKeyValPairs();
}
