<?php
  date_default_timezone_set('Africa/Tripoli');

  $username = "gateway"; 
  $password = "gateway66527"; 
  $host = "localhost"; 
  $dbname = "gatewaysms"; 

  $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
     
  try 
  { 
      $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
  } 
  catch(PDOException $ex) 
  { 
    die("Failed to connect to the database: " . $ex->getMessage()); 
  } 
     
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
//  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
     
  if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) 
  { 
    function undo_magic_quotes_gpc(&$array) 
    { 
      foreach($array as &$value) 
      { 
        if(is_array($value)) 
        { 
          undo_magic_quotes_gpc($value); 
        } 
        else 
        { 
          $value = stripslashes($value); 
        } 
      } 
    } 
     
    undo_magic_quotes_gpc($_POST); 
    undo_magic_quotes_gpc($_GET); 
    undo_magic_quotes_gpc($_COOKIE); 
  }
     
//  header('Content-Type: text/html; charset=utf-8');

  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache")
?>