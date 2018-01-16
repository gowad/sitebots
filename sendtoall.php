<?php
define('BOT_TOKEN', 'توكن');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  $parameters["method"] = $method;
  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}
function exec_curl_request($handle) {
  $response = curl_exec($handle);
  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }
  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);
  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
 throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }
  return $response;
}
function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
 error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  $parameters["method"] = $method;
  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
  return exec_curl_request($handle);
}
function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    $admin = ايدي;
    $matches = explode(' ', $text);
    $substr = substr($text, 0,7 );
    if (strpos($text, "/start") === 0) {
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => "سلام 👋

🔹 انشاء بوتات سايت 

🔸 باستخدام هذه الخدمة  انشاء بوتات سايت الخ..

🔹لصنع البوت اضغط على  زر ✅ صنع بوت

🔹 /help - للمساعدة ✅
🔹 /ch - للانضمام لقناة البوت ✅

[🌐اضغط هنا وتابع جديدنا🌐](telegram.me/Teamiraq)","parse_mode"=>"MARKDOWN",'reply_markup' => array(
        'keyboard' => array(array('✅ صنع بوت'),array('🚫 حذف البوت')),
        'resize_keyboard' => true)));
if (strpos($users , $chat_id) !== false)
			{ 
			
			}
		else { 
			$myfile2 = fopen("members.txt", "a") or die("Unable to open file!");	
			fwrite($myfile2, $chat_id."\n");
			fclose($myfile2);
		     }
        if($chat_id == $admin)
        {
          if(!file_exists('tokens.txt')){
        file_put_contents('tokens.txt',"");
           }
        $tokens = file_get_contents('tokens.txt');
        $part = explode("\n",$tokens);
       $tcount =  count($part)-1;
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id,  "text" => "عدد البوتات <code>".$tcount."</code>","parse_mode"=>"HTML"));
        }
    }else if ($text == "/help") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "اهلا وسهلا بك صديقي في قائمةالمساعده 💡

1️⃣- قم بلضغط على صنع بوت 

2️⃣-قم بارسال التوكن الخاص بك من [ @BotFather ]

3️⃣-سيتم صنع بوتك الخاص !
","parse_mode"=>"html"));	}else if ($text == "/ch") {      
apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => "[اضغط هنا للانضمام الى قناة البوت 😃](telegram.me/b7_78)", 'parse_mode' => "Markdown"));
	}else if ($matches[0] == "/setvip") {
		$vipidbot =$matches[1];
		$vipbot =$matches[2];
		file_put_contents($vipidbot.'/vip.txt',$vipbot);
		apiequest("sendmessage", array('chat_id' => $chat_id, "text" => "<i>تمت الترقية</i>","parse_mode" =>"HTML"));
	}else if ($matches[0] == "/sendtoall"&&$chat_id == $admin) {
    $texttoall =$matches[1];
	$sendtestall = str_replace("+"," ",$texttoall);
		$ttxtt = file_get_contents('members.txt');
		$membsidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			apiRequest("sendMessage", array('chat_id' => $membersidd[$y], "text" => $sendtestall,"parse_mode" =>"HTML"));
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Message Sent To</b> 
<code>".$memcout."</code>
<b>Members Sir</b>
------------------","parse_mode" =>"HTML"));
    }else if ($matches[0] == "/update"&& strpos($matches[1], ":")) {				
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "جاري التحقق منن التوكن ♻️"));		
		$id = $message['chat']['id'];
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("توكن",$matches[1],$phptext);
        $phptext = str_replace("ايدي",$chat_id,$phptext);
        file_put_contents($id.'/sendtoall.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$maches[1].'$texttwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'/setwebhook?url=https://🚫.000webhostapp.com/'.$chat_id.'/swndtoall.php');
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🚀 تم  ♻️"));
    }else if ($text == "🔙 رجوع") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🔃 تم الرجوع للقائمة الرئيسية💡","parse_mode"=>"Markdown",'reply_markup' => array(
        'keyboard' => array(array('✅ صنع بوت'),array('🚫 حذف البوت')),
        'resize_keyboard' => true)));
	}else if ($text == "🚫 حذف البوت") {
	if (is_dir($chat_id)) { 
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "جاري حذف بوتك الخاص 🔄","parse_mode"=>"Markdown"));
	  $objects = scandir($chat_id);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($chat_id."/".$object) == "dir") rrmdir($chat_id."/".$object); else unlink($chat_id."/".$object);
       }
     }
     reset($objects);
     rmdir($chat_id); 
	  apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🚀 تم حذف الروبوت ♻️"));
	}}else if ($text == "✅ صنع بوت") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "ارسل توكن بوتك الان 💡","parse_mode"=>"Markdown",'reply_markup' => array(
        'keyboard' => array(array('🔙 رجوع')),
        'resize_keyboard' => true)));
	}else if ($matches[0] != "/update" && $matches[1] == "") {
      if (strpos($text, ":")) {
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "جاري التحقق من التوكن ♻️"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $chat_id;
    
   $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null){
      
      if(file_exists($id)==false && in_array($chat_id,$membersid)==false){
          
        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);
     mkdir($id, 0700);
        file_put_contents($id.'/vip.txt',"free");
		file_put_contents($id.'/ad_vip.txt',"PlusTM");
        file_put_contents($id.'/step.txt',"none");
		file_put_contents($id.'/users.txt',"");
		file_put_contents($id.'/token.txt',"$text");
        file_put_contents($id.'/start.txt',"");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("توكن",$text,$phptext);
        $phptext = str_replace("ايدي",$chat_id,$phptext);
        file_put_contents($token.$id.'/sendtoall.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=https://🚫🚫.000webhostapp.com/'.$chat_id.'/sendtoall.php');
    $unstalled = "
💡 تم صنع بوت للحماية الخاص بك 😻👇🏼";
    
    $bot_url    = "https://api.telegram.org/botتوكن/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 
$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
$output = curl_exec($ch); 
    
    
    $textinstall = "
😃 اهلا بك في بوتك الشخصي ";
  $install = "http://api.telegram.org/bot".$matches[0]."/sendMessage?chat_id=".$chat_id."&text=".$textinstall;
  $json = file_get_contents($install);
      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "لا يمكنك صنع اكثر من بوت واحد !💡"));
      }
    }
      
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن غير صحيح ❌
يرجى ارسال التوكن الصحيح ✅"));
    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن غير صحيح ❌
يرجى ارسال التوكن الصحيح ✅"));
}
        }else if ($matches[0] != "/update"&&$matches[1] != ""&&$matches[2] != "") {
          
        if (strpos($text, ":")) {
          
          
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "جاري التحقق من التوكن ♻️"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $matches[1].$matches[2];
    
    $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null ){
        
      if(file_exists($id)==false && in_array($id,$membersid)==false){
        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);
     mkdir($id, 0700);
        file_put_contents($id.'/users.txt',"");
		file_put_contents($id.'/vip.txt',"free");
		file_put_contents($id.'/ad_vip.txt',"PlusTM");
        file_put_contents($id.'/step.txt',"none");
		file_put_contents($id.'/token.txt',"$text");
        file_put_contents($id.'/start.txt',"");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("توكن",$matches[0],$phptext);
        $phptext = str_replace("ايدي",$matches[1],$phptext);
        file_put_contents($token.$id.'/sendtoall.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=https://🚫🚫.000webhostapp.com/'.$id.'/sendtoall.php');
    $unstalled = "
💡 تم صنع بوت للحماية الخاص بك 😻👇🏼";
    
    $bot_url    = "https://api.telegram.org/botتوكن/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 
$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
$output = curl_exec($ch); 
	$textinstall = "
😃 اهلا بك في بوتك الشخصي ";
  $install = "http://api.telegram.org/bot".$matches[0]."/sendMessage?chat_id=".$chat_id."&text=".$textinstall;
  $json = file_get_contents($install);
      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "لا يمكنك صنع اكثر من. بوت واحد !💡"));
      }
    }
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن غير صحيح ❌
يرجى ارسال التوكن الصحيح ✅"));
    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن غير صحيح ❌
يرجى ارسال التوكن الصحيح ✅"));
}
        } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => '❌ عذرا هناك خطا! 
🌀ارسل /start من فضلك 💡'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => '❌ عذرا هناك خطا! 
🌀ارسل /start من فضلك 💡'));
  }
}
define('WEBHOOK_URL', 'https://🚫🚫.000webhostapp.com/sendtoall.php');
if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}
$content = file_get_contents("php://input");
$update = json_decode($content, true);
if (!$update) {
  // receive wrong update, must not happen
  exit;
}
if (isset($update["message"])) {
  processMessage($update["message"]);
}
