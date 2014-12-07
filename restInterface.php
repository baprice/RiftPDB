<?php

require_once('ormLayer.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  // GET means either instance look up, index generation, or deletion

  // Following matches instance URL in form
  // /todo.php/<id>

  if ((count($path_components) >= 2) &&
      ($path_components[1] != "")) {

    // Interpret <id> as integer
    $todo_id = intval($path_components[1]);

    // Look up object via ORM
    $todo = ormLayer::findByID($todo_id);

    if ($todo == null) {
      // ormLayer not found.
      header("HTTP/1.0 404 Not Found");
      print("ormLayer id: " . $todo_id . " not found.");
      exit();
    }

    // Check to see if deleting
    if (isset($_REQUEST['delete'])) {
      $todo->delete();
      header("Content-type: application/json");
      print(json_encode(true));
      exit();
    } 

    // Normal lookup.
    // Generate JSON encoding as response
    header("Content-type: application/json");
    print($todo->getJSON());
    exit();

  }

  // ID not specified, then must be asking for index
  header("Content-type: application/json");
  print(json_encode(ormLayer::getAllIDs()));
  exit();

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

  // Either creating or updating

  // Following matches /todo.php/<id> form
  if ((count($path_components) >= 2) &&
      ($path_components[1] != "")) {

    //Interpret <id> as integer and look up via ORM
    $todo_id = intval($path_components[1]);
    $todo = ormLayer::findByID($todo_id);

    if ($todo == null) {
      // ormLayer not found.
      header("HTTP/1.0 404 Not Found");
      print("ormLayer id: " . $todo_id . " not found while attempting update.");
      exit();
    }

    // Validate values
    $new_title = false;
    if (isset($_REQUEST['title'])) {
      $new_title = trim($_REQUEST['title']);
      if ($new_title == "") {
  header("HTTP/1.0 400 Bad Request");
  print("Bad title");
  exit();
      }
    }

   

    if (isset($_REQUEST['complete'])) {
      $new_complete = true;
    } else {
      $new_complete = false;
    }

    // Update via ORM
    if ($new_title) {
      $todo->setTitle($new_title);
    }
    

    // Return JSON encoding of updated ormLayer
    header("Content-type: application/json");
    print($todo->getJSON());
    exit();
  } else {

    // Creating a new ormLayer item

    // Validate values
    if (!isset($_REQUEST['id'])) {
      header("HTTP/1.0 400 Bad Request");
      print("Missing id");
      exit();
    }
    
    $id = trim($_REQUEST['id']);
    if ($id == "") {
      header("HTTP/1.0 400 Bad Request");
      print("Bad id");
      exit();
    } 

    if (!isset($_REQUEST['name'])) {
      header("HTTP/1.0 400 Bad Request");
      print("Missing name");
      exit();
    }
    
    $name = trim($_REQUEST['name']);
    if ($name == "") {
      header("HTTP/1.0 400 Bad Request");
      print("Bad name");
      exit();
    }

    if (!isset($_REQUEST['structure'])) {
      header("HTTP/1.0 400 Bad Request");
      print("Missing structure");
      exit();
    }
    
    $structure = trim($_REQUEST['structure']);
    if ($structure == "") {
      header("HTTP/1.0 400 Bad Request");
      print("Bad structure");
      exit();
    }
    

    // Create new ormLayer via ORM
    $new_todo = ormLayer::create($id, $name, $structure);
    // Report if failed
    if ($new_todo == null) {
      header("HTTP/1.0 500 Server Error");
      print("Server couldn't create new todo.");
      exit();
    }
    
    //Generate JSON encoding of new ormLayer
    header("Content-type: application/json");
    print($new_todo->getJSON());
    exit();
  }
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");

?>