<?php

function PingStart() {
    $charInfo = CharacterInformation::getInstance();
    $pageTitle = "Send Ping";
    date_default_timezone_set('UTC');

    if (!$charInfo->officer) {
        requestError(403);
    }

    if (isset($_POST['PingText'])) {

        #Validation and redirection to send to jabber
        $pingtextERR="";
        $valid = true;

        if (empty($_POST["PingText"])) {
            $pingtextERR = "Text is required";
            $valid = false; //false
            echo "<script type='text/javascript'>alert('$pingtextERR');</script>";
        }
        #if valid then redirect
        if($valid){
            sendping();
        }
    }

    include('templates/admin/sendping.html');
}

function sendping() {
    $charInfo = CharacterInformation::getInstance();


    $host = $GLOBALS['jaxl_host'];
    $user = $GLOBALS['jaxl_user'];
    $pass = $GLOBALS['jaxl_pass'];

    $InitialPing = $_POST["PingText"];

    #Needs Jaxl client
    require './static/JAXL/jaxl.php';

    // add logging text ot the bottom of the ping
    $ping = "\n{$InitialPing}\n\n##### \n SENT BY: $charInfo->charName; \n TO: online.STAHP; \n WHEN: ". date('d/m-y, ') . "at" . date(' G:i ') ."EVE Time" ." \n #####";

    # Create Client
    $client = new JAXL(array(
      'log_path' => 'jaxl.log',
      'jid' => $user.'@'.$host,
      'pass' => $pass,
      'log_level' => JAXL_INFO,
      'auth_type' => 'DIGEST-MD5'
    ));

     // Add Callbacks
     $client->add_cb('on_auth_success', function() use ($host, $client, $ping)
     {
         $client->send_chat_msg('stahp.ovh/announce/online', $ping);  //Hardcoded stahp, as jaxl connect to hero.stahp. (not $host.'/announce/online')
         $client->send_end_stream();                                  //But the message needs to be sent to stahp.ovh.
         _info("on_auth_success SENT");                               //This is caused by auth function with wordpress
     });                                                              //Can be changed by new combined auth Database

     $client->add_cb('on_auth_failure', function($reason) use ($client)
     {
         $client->send_end_stream();
         _info("got on_auth_failure cb with reason: $reason");
     });

     $client->add_cb('on_disconnect', function() use ($client)
     {
         _info("got on_disconnect cb");

     });
     $client->add_cb('on_connect_error', function($reason) use ($client)
     {
         _info("Couldn't connect with reason $reason");

     });
     // Startup Client
     $client->start();


}
