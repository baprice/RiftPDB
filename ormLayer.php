<?php
date_default_timezone_set('America/New_York');

class ormLayer
{
  private $id;
  private $title;

  public static function create($title, $note, $project, $due_date, $priority, $complete) {
    $mysqli = new mysqli("***REMOVED***", "kmp", "comp426", "comp426fall14db");

    if ($due_date == null) {
      $dstr = "null";
    } else {
      $dstr = "'" . $due_date->format('Y-m-d') . "'";
    }

    if ($complete) {
      $cstr = "1";
    } else {
      $cstr = "0";
    }

    $result = $mysqli->query("insert into a6_Molecules values (0, " .
           "'" . $mysqli->real_escape_string($title) . "', " .
           "'" . $mysqli->real_escape_string($note) . "', " .
           "'" . $mysqli->real_escape_string($project) . "', " .
           $dstr . ", " .
           $priority . ", " .
           $cstr . ")");
    
    if ($result) {
      $id = $mysqli->insert_id;
      return new ormLayer($id, $title, $note, $project, $due_date, $priority, $complete);
    }
    return null;
  }

  public static function findByID($id) {
    $mysqli = new mysqli("***REMOVED***", "kmp", "comp426", "comp426fall14db");

    $result = $mysqli->query("select * from a6_Molecules where id = " . $id);
    if ($result) {
      if ($result->num_rows == 0) {
  return null;
      }

      $todo_info = $result->fetch_array();

      if ($todo_info['due_date'] != null) {
  $due_date = new DateTime($todo_info['due_date']);
      } else {
  $due_date = null;
      }

      if (!$todo_info['complete']) {
  $complete = false;
      } else {
  $complete = true;
      }

      return new ormLayer(intval($todo_info['id']),
          $todo_info['title'],
          $todo_info['note'],
          $todo_info['project'],
          $due_date,
          intval($todo_info['priority']),
          $complete);
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

  private function __construct($id, $title, $note, $project, $due_date, $priority, $complete) {
    $this->id = $id;
    $this->title = $title;
    $this->note = $note;
    $this->project = $project;
    $this->due_date = $due_date;
    $this->priority = $priority;
    $this->complete = $complete;
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
    if ($this->due_date == null) {
      $dstr = null;
    } else {
      $dstr = $this->due_date->format('Y-m-d');
    }

    $json_obj = array('id' => $this->id,
          'title' => $this->title,
          'note' => $this->note,
          'project' => $this->project,
          'due_date' => $dstr,
          'priority' => $this->priority,
          'complete' => $this->complete);
    return json_encode($json_obj);
  }
}