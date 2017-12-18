<?php


 ?>
    	<script language="javascript" >console.log('data : <?php print_r($_REQUEST) ?>');</script><?php exit;
?>








require __DIR__ . '/vendor/autoload.php';
use Minishlink\WebPush\WebPush;
// here I'll get the subscription endpoint in the POST parameters
// but in reality, you'll get this information in your database
// because you already stored it (cf. push_subscription.php)
//$subscription = json_decode(file_get_contents('php://input'), true);

try{
    $db = new PDO('sqlite:DB.sqlite');
}
catch(PDOException $e){
    exit($e->getMessage());
}
$myQuery = "SELECT * FROM subscribers LIMIT 0, 1";
try{
     $result = $db->query($myQuery)->fetch(PDO::FETCH_ASSOC);
    if($result['id'] == NULL || $result['id'] == ""){ ?>
    	<script language="javascript" >//console.log('data : <?php //echo ''; ?>');</script><?php exit;
     }else{
         $subscriber = $result;
     }
}
catch(PDOException $e){
     exit($e->getMessage());
}

$auth = array(
    'VAPID' => array(
        'subject' => 'https://web-push-codelab.glitch.me/',
        'publicKey' => 'BPEp_jqXPIyLXy-C3B3yhgbS83VtuJMmwvjDNhYauqj30PWBNZ4dX_qumvMCECWDI58xmvC-8oXK7GEmZ5cUyoM',
        'privateKey' => 'ClTT_v4B99lUtpisizKnfXowRekHKV8TiuuOmkIQoQA', // in the real world, this would be in a secret file
    ),
);
//exit($subscriber['endpoint'].' : '.$subscriber['auth'].' : '.$subscriber['p256dh']);
$webPush = new WebPush($auth);
//this code was modified from the tutorial to make it more dynamic.
//hardcoding the serviceworker push notification would not be a great practice in a real-world application
$res = $webPush->sendNotification(
    $subscriber['endpoint'],
    '{"title":"hello","msg":"yes it works","icon":"images/icon.png","badge":"images/badge.png","url":"https://developers.google.com/web/"}',
    // str_replace(['_', '-'], ['/', '+'],$subscriber['p256dh']),
    // str_replace(['_', '-'], ['/', '+'],$subscriber['auth']),
    str_replace(['_', '-'], ['/', '+'],$_REQUEST['token']),
    str_replace(['_', '-'], ['/', '+'],$_REQUEST['key']),
    true
);
// handle eventual errors here, and remove the subscription from your server if it is expired


var_dump($res);

?>