<?php

require_once 'vendor/autoload.php';
require_once 'utilities.php';
require_once 'CLog.php';

use JPush\Model as M;
use JPush\JPushClient;
use JPush\JPushLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use JPush\Exception\APIConnectionException;
use JPush\Exception\APIRequestException;

class Notification {
	private static $br = '<br/>';
	private static $spilt = ' - ';
    private static $master_secret = '';
	private static $app_key='';
	private static $client;
	public static $debug = false;

	/**
     * setting up environment
     * @param 
     */
    private static function init() {
    	if(!JPushLog::getLogHandlers()){
    		if (defined('SAE_TMP_PATH')) 
				JPushLog::setLogHandlers(array(new StreamHandler(SAE_TMP_PATH . '/jpush.log', Logger::DEBUG)));
			else 
				JPushLog::setLogHandlers(array(new StreamHandler('jpush.log', Logger::DEBUG)));
    	}
		
		if(!self::$client) 
			self::$client = new JPushClient(self::$app_key, self::$master_secret);
    }

    /**
     * 
     * @param $circle_id
     */
    private static function getRegistrationIDInCircle($circle_id) {
    	$link = connect_db();
		$ids = array();
		if ($link) {
			$sql = sprintf("SELECT registration_id
							FROM relative, user
							WHERE relative.circle_id='%s'
								AND registration_id IS NOT NULL
								AND relative.user_id=user.user_id",
							mysql_real_escape_string($circle_id));
			$result = mysql_query($sql);

			if(self::$debug) echo $sql . self::$br;
			if ($result) {
				while ($row = mysql_fetch_assoc($result)) {
					if (!empty($row['registration_id']))
						array_push($ids, $row['registration_id']);
				}
				if(self::$debug) {var_dump($ids); echo self::$br;}
			}
		}
		return $ids;
    }

    /**
     * 
     * @param $circle_id
     */
    public static function push($ids) {
    	//$ids = self::getRegistrationIDInCircle($circle_id);
    	//easy push
    	self::init();
		try {
			$pushpayload = self::$client->push()
		        ->setPlatform(M\all)
		        ->setAudience(M\registration_id($ids))
		        ->setNotification(M\notification('Hi, JPush', M\ios("circle updated", "happy", "+1")));
			$json = $pushpayload->getJSON();
			$result = $pushpayload->send();

sae_debug("sending notification to " . json_encode($ids));
			
		    //CLog::debug("push pending", $json);
		    if (self::$debug) {
		    	//CLog::debug("push success", $json);
		    	echo $json . self::$br;
		    	echo 'Push Success.' . self::$br;
		    	echo 'sendno : ' . $result->sendno . self::$br;
		    	echo 'msg_id : ' .$result->msg_id . self::$br;
		    	echo 'Response JSON : ' . $result->json . self::$br;
		    }
		} catch (APIRequestException $e) {
sae_debug("APIRequestException");
		    if (self::$debug) {
		    	echo 'Push Fail.' . self::$br;
		    	echo 'Http Code : ' . $e->httpCode . self::$br;
		    	echo 'code : ' . $e->code . self::$br;
		    	echo 'Error Message : ' . $e->message . self::$br;
		    	echo 'Response JSON : ' . $e->json . self::$br;
		    	echo 'rateLimitLimit : ' . $e->rateLimitLimit . self::$br;
		    	echo 'rateLimitRemaining : ' . $e->rateLimitRemaining . self::$br;
		    	echo 'rateLimitReset : ' . $e->rateLimitReset . self::$br;
		    }
		} catch (APIConnectionException $e) {
sae_debug("APIConnectionException");
		    if (self::$debug) {
		    	echo 'Push Fail: ' . self::$br;
		    	echo 'Error Message: ' . $e->getMessage() . self::$br;
		    	//response timeout means your request has probably be received by JPUsh Server,please check that whether need to be pushed again.
		    	echo 'IsResponseTimeout: ' . $e->isResponseTimeout . self::$br;
		    }
		}
		if(self::$debug) echo self::$br . '-------------' . self::$br;
    }
}

if(Notification::$debug) Notification::push('2');