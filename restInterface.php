<?php

require_once('ormLayer.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.

if ($_SERVER['REQUEST_METHOD'] == "GET") {
  // GET means either instance look up, index generation, or deletion

  // Following matches instance URL in form
  // /restInterface.php/<id>

  if ((count($path_components) >= 2) &&
      ($path_components[1] != "")) {

    // Interpret <id> as integer
    $current_id = intval($path_components[1]);

    // Look up object via ORM
    $current = ormLayer::findByID($current_id);

    if ($current == null) {
      // Orm representation not found.
      header("HTTP/1.0 404 Not Found");
      print("ormLayer id: " . $current_id . " not found.");
      exit();
    }

    // Check to see if deleting
    if (isset($_REQUEST['delete'])) {
      $current->delete();
      header("Content-type: application/json");
      print(json_encode(true));
      exit();
    } 

    // Normal lookup.
    // Generate JSON encoding as response
    header("Content-type: application/json");
    print($current->getJSON());
    exit();

  }

  // ID not specified, then must be asking for index
  header("Content-type: application/json");
  print(json_encode(ormlayer::getAllIDs()));
  exit();

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

  // Either creating or updating

  // Following matches /restInterface.php/<id> form
  if ((count($path_components) >= 2) &&
      ($path_components[1] != "")) {

    //Interpret <id> as integer and look up via ORM
    $current_id = intval($path_components[1]);
    $current = ormlayer::findByID($current_id);

    if ($current == null) {
      // Orm representation not found.
      header("HTTP/1.0 404 Not Found");
      print("Orm Layer id: " . $current_id . " not found while attempting update.");
      exit();
    }

    // Validate values
    $new_name = false;
    if (isset($_REQUEST['name'])) {
      $new_name = trim($_REQUEST['name']);
      if ($new_name == "") {
	header("HTTP/1.0 400 Bad Request");
	print("Bad name");
	exit();
      }
    }

    
    // Update via ORM
    if ($new_name) {
      $current->setName($new_name);
    }
    
    // Return JSON encoding of updated ormLayer
    header("Content-type: application/json");
    print($current->getJSON());
    exit();
  } else {

    // Creating a new Todo item

    // Validate values
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

    

    // Create new orm representaiton via ORM
    $new_current = ormlayer::create($name);

    // Report if failed
    if ($new_current == null) {
      header("HTTP/1.0 500 Server Error");
      print("Server couldn't create new orm representation.");
      exit();
    }
    
    //Generate JSON encoding of new Orm Representaiton
    header("Content-type: application/json");
    print($new_current->getJSON());
    exit();
  }
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");

?>