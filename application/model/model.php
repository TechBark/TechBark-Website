<?php

class Model {
/**
 * @param object $db A PDO database connection
 */
 function __construct($db) {
  try {
   $this->db = $db;
   }
  catch(PDOException $e) {
   exit('Database connection could not be established.');
   }
  }

 public function getMembers() {
  $sql = "SELECT firstName, lastName, email FROM members";
  $query = $this->db->prepare($sql);
  $query->execute();
  return $query->fetchAll();
  }

 public function getUserProjects($username) {
  $sql = "SELECT projects.projectName FROM users LEFT JOIN userProjects ON users.userID=userProjects.userID LEFT JOIN projects ON userProjects.projectID=projects.projectID WHERE username=:username";
  $query = $this->db->prepare($sql);
  $parameters = array(':username' => $username);
  $query->execute($parameters);
  return $query->fetchAll();
  }
 }
