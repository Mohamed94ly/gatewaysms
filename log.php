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
    $token = 'halla202011';
    $ipGateway = '10.100.1.141';
    $url = 'http://' . $ipGateway . ':8766/?number='. $phone .'&message='. $msg .'&token='. $token;

    /*
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($curl);
    curl_close($curl);
    */
    file_get_contents($url);
    // var_dump($resp);
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
    $url = 'https://api.telegram.org/bot1477259072:AAFC2FrgnLBtSn3iRyeAVwr7NiLQei-FQVY/sendMessage?chat_id=-1001362035270&text=' . 'Count SMS Today = '. $num_daily;

    file_get_contents($url);

    echo $num_daily;
    echo $resp;
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