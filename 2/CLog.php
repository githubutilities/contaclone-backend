<?php

require_once 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CLog {


	private static $tag = "CLog";
	private static $logHandler;
	private static $logger;

	public static function setLogHandler($handler) {
		self::$logHandler = $handler;
	}

	public static function getLogHandler() {
		self::$logHandler = array(new StreamHandler('clog.log', Logger::DEBUG));
		return self::$logHandler;
	}

	public static function getLogger() {
		if (!self::$logger) 
			self::$logger = new Logger(self::$tag, self::getLogHandler());
		return self::$logger;
	}

	public static function debug($tag, $message) {
		if (!$tag) $tag = self::$tag;
		if (defined('SAE_APPNAME')) sae_debug($tag . ":" . $message);
		else {
			self::getLogger()->debug($tag, array($message));
		}
	}

}

//CLog::debug("TAG", "HI");