<?php

  if(isset($_GET['phone'])){
    insert_db();
  }

  if(isset($_GET['countdaily'])){
    get_countDaily();
  }

  if(isset($_GET['smsdaily'])){
    get_smsdaily();
  }

  function insert_db(){
    require 'config.php';

    $query = "INSERT INTO logsms (phone, msg) VALUES (:phone, :msg)";

    $msg = str_replace(' ','%20',$_GET['message']);

    $params = array(
      ':phone'    =>   $_GET['phone'],
      ':msg'      =>   $_GET['message'],
    );

    try {
      $stmt   = $db->prepare($query);
      $result = $stmt->execute($params);

      send_sms($msg, $_GET['phone']);
    }catch (PDOException $ex) {
      echo $ex;
    }
  }

  function send_sms($msg, $phone){
    $url = '';

    file_get_contents($url);
  }

  function get_countDaily(){
    require 'config.php';

    $query = "SELECT count(*) as num_daily FROM logsms WHERE date(create_at) = CURDATE();";

    try {
      $stmt   = $db->prepare($query);
      $result = $stmt->execute();
    }catch (PDOException $ex) {
      echo $ex;
    }

    $data = array();
    $row = $stmt->fetch();
    if ($row){
      send_telgram($row["num_daily"]);
    }
  }

  function send_telgram($num_daily){
    $url = '';

    file_get_contents($url);
  }

  function get_smsdaily(){
    require 'config.php';

    $query = "SELECT * FROM logsms WHERE date(create_at) = CURDATE();";

    try {
      $stmt   = $db->prepare($query);
      $result = $stmt->execute();
    }catch (PDOException $ex) {
      echo $ex;
    }

    $data = array();
    $rows = $stmt->fetchall();
    if ($rows){
      foreach ($rows as $row){
        $nestedData = array();
        $nestedData['phone'] =  $row["phone"];
        $nestedData['message'] =  $row["msg"];
        $data[] = $nestedData;
      }

      echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
  }