<?php
/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 *
 */
class View extends Controller {
 public function index() {
  require APP . 'view/_templates/header.php';
  require APP . 'view/_templates/footer.php';
  }

 public function profile() {
  }

 public function membership() {
  require APP . 'view/_templates/header.php';
  $members = $this->model->getMembers();

  echo "<table>";
  echo "<tr>";
  echo "<th>Name</th><th>Email</th>";
  echo "</tr>";
  foreach($members as $member) {
   echo "<tr>";
   echo "<td>";
   echo $member->lastName . ", " . $member->firstName;
   echo "</td><td><a href='mailto:" . $member->email . "'>" . $member->email . "</a></td>";
   echo "</tr>";
   }
  echo "</table>";

  require APP . 'view/_templates/footer.php';
  }
 }
