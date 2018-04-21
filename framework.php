<?php

/* This software is under GNU - General Public License v3.0 licenced to JamesConstruct (A.K.A DisShowák). More info https://github.com/JamesConstruct/StandaCoin/blob/master/LICENSE or http://standacoin.esy.es/StandaPay */

class App {
    public function __construct($id, $password, $paymenturl) {
        $this->id = $id;
        $this->pass = $password;
        $this->paymenturl = $paymenturl;
    }
    public function PayUrl($keyresponse) {
        if ($keyresponse["success"]) { // pokud bylo vytvoření klíče úspěšné
            $payurl = "http://standacoin.esy.es/pay/" . $keyresponse["key"]; // vytvoření adresy pro platbu
        } else {
            $payurl = "";
        }
        return $payurl;
    }
}
class Key {
    public static function create($app, $name, $amount, $description) {
        $name = str_replace(" ", "%20", $name);
        $description = str_replace(" ", "%20", $description);
        $data = array(  "key"=>$app->key,
                        "name"=>$name,
                        "amount"=>$amount,
                        "description"=>$description,
                        "url"=>$app->paymenturl
                );
        $url = "http://standacoin.esy.es/API/createkey";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = json_decode(curl_exec($ch), true);
        return $response;
    }
}
class Payment {
    public static function GetInfo($id) {
        $url = "http://standacoin.esy.es/API/payment/" . $id;
        $response = file_get_contents($url);
        $decoded = json_decode($response, true);
        return $decoded;
    }
}
/*  example usage:
    require("framework.php");
    $app = new App("a65ga65fh5rf5ga", "MyPass", "http://example.org/done/?type=StandaCoin&");
    $key = Key::create($app, "Cool platba", 0.256, "Tohle je příkladová platba ;)");
    $payurl = $app->PayUrl($key);
    /***************************
    $payment = Payment::GetInfo($received_id);
*/

?>