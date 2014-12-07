<?php
date_default_timezone_set('America/New_York');

class ormLayer
{
  private $id;
  private $name;
  private $structure;

  public static function create($id,$name,$structure) {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "***REMOVED***", "***REMOVED***db");


    $result = $mysqli->query("INSERT into a6_Molecules VALUES ( '" . $mysqli->real_escape_string($id) 
            . "', '" . $mysqli->real_escape_string($name)
            . "', '" . $mysqli->real_escape_string($structure) . "')");
    
    var_dump($result);

    if ($result) {
      return new ormLayer($id, $name, $structure);
    }
    return null;
  }

  public static function findByID($id) {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "***REMOVED***", "***REMOVED***db");

    $result = $mysqli->query("SELECT * FROM a6_Molecules where id = " . $id);
    if ($result) {
      if ($result->num_rows == 0) {
  return null;
      }

      $todo_info = $result->fetch_array();

      return new ormLayer(intval($todo_info['id']), $todo_info['name'],
          $todo_info['structure']);
    }
    return null;
  }

  public static function getAllIDs() {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "***REMOVED***", "***REMOVED***db");

    $result = $mysqli->query("SELECT id FROM a6_Molecules");
    $id_array = array();

    if ($result) {
      while ($next_row = $result->fetch_array()) {
  $id_array[] = intval($next_row['id']);
      }
    }
    return $id_array;
  }

  private function __construct($id, $name, $structure) {
    $this->id = $id;
    $this->name = $name;
    $this->structure = $structure;
  }

  

  private function update() {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "***REMOVED***", "***REMOVED***db");

    if ($this->due_date == null) {
      $dstr = "null";
    } else {
      $dstr = "'" . $this->due_date->format('Y-m-d') . "'";
    }

    if ($this->complete) {
      $cstr = "1";
    } else {
      $cstr = "0";
    }

    $result = $mysqli->query("update a6_Molecules set " .
           "title=" .
           "'" . $mysqli->real_escape_string($this->title) . "', " .
           "note=" .
           "'" . $mysqli->real_escape_string($this->note) . "', " .
           "project=" .
           "'" . $mysqli->real_escape_string($this->project) . "', " .
           "due_date=" . $dstr . ", " .
           "priority=" . $this->priority . ", " .
           "complete=" . $cstr . 
           " where id=" . $this->id);
    return $result;
  }

  public function delete() {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "***REMOVED***", "***REMOVED***db");
    $mysqli->query("delete from a6_Molecules where id = " . $this->id);
  }

  public function getJSON() {

    $json_obj = array('id' => $this->id,
          'name' => $this->name,
          'structure' => $this->structure);
    return json_encode($json_obj);
  }
}