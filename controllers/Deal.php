<?php

class Deal {
  public $id;
  public $name;
  public $time;

  //Instantiate a new deal
  function __construct($id, $name, $time){
    $this->id = $id;
    $this->name = $name;
    $this->time = $time;
  }

  //Get string representation of object
  public function __toString()
  {
    return "ID: ".$this->id." Name: ".$this->name." Time: ".$this->time;
  }

  //Get an associative array of all field names and values
  public function getOutputData()
  {
    return ["id"=>$this->id,
            "name"=>$this->name,
            "time"=>$this->time];
  }

}
