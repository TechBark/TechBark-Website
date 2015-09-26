<?php
/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 *
 */
class Home extends Controller {
/**
 * PAGE: index
 * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
*/
 public function index() {
  // load views
  require APP . 'view/_templates/header.php';
  require APP . 'view/home/index.php';
  require APP . 'view/_templates/footer.php';
  }

/**
 * PAGE: events
 * This method handles what happens when you move to http://yourproject/home/events
*/
 public function events() {
  require APP . 'view/_templates/header.php';
  echo "This is the events page";
  require APP . 'view/_templates/footer.php';
  }

 }
