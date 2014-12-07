<?php

class ormLayer
{
  private $id;
  private $name;
 

  public static function create($name) {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "comp426", "***REMOVED***db");

    
    $result = $mysqli->query("insert into a6_Molecules values (0, " .
			     "'" . $mysqli->real_escape_string($name) . ")");
    
    if ($result) {
      $id = $mysqli->insert_id;
      return new ormlayer($id, $name);
    }
    return null;
  }

  public static function findByID($id) {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "comp426", "***REMOVED***db");

    $result = $mysqli->query("select * from a6_Molecules where id = " . $id);
    if ($result) {
      if ($result->num_rows == 0) {
	return null;
      }

      $current_info = $result->fetch_array();

      

      return new ormLayer(intval($todo_info['id']),
		      $current_info['name'];
    }
    return null;
  }

  public static function getAllIDs() {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "comp426", "***REMOVED***db");

    $result = $mysqli->query("select id from a6_Molecules");
    $id_array = array();

    if ($result) {
      while ($next_row = $result->fetch_array()) {
	$id_array[] = intval($next_row['id']);
      }
    }
    return $id_array;
  }

  private function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
  }

  public function getID() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

 

  public function setName($name) {
    $this->name = $name;
    return $this->update();
  }

  

  private function update() {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "comp426", "***REMOVED***db");

    
    $result = $mysqli->query("update a6_Molecules set " .
			     "name=" .
			     "'" . $mysqli->real_escape_string($this->name) . 
			     " where id=" . $this->id);
    return $result;
  }

  public function delete() {
    $mysqli = new mysqli("***REMOVED***", "***REMOVED***", "comp426", "***REMOVED***db");
    $mysqli->query("delete from a6_Molecules where id = " . $this->id);
  }

  public function getJSON() {
    
    $json_obj = array('id' => $this->id,
		      'name' => $this->name);
    return json_encode($json_obj);
  }
}