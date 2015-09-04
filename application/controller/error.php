<?php
/**
 * Class Error
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 *
 */
class Error extends Controller {
/**
 * PAGE: index
 * This method handles the error page that will be shown when a page is not found
 */
 public function index() {
  // load views
  require APP . 'view/_templates/header.php';
  require APP . 'view/error/index.php';
  require APP . 'view/_templates/footer.php';
  }
 }
