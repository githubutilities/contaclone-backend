<?php

require_once '../vendor/autoload.php';

use JPush\Model as M;
use JPush\JPushClient;

class ReportTest extends PHPUnit_Framework_TestCase {
    public $appKey = "";
    public $masterSecret = '';

    function testReport() {
        $client = new JPushClient($this->appKey, $this->masterSecret);
        $result = $client->report('769835449');
        $this->assertTrue($result->isOk === true);
    }

    function testReportMore() {
        $client = new JPushClient($this->appKey, $this->masterSecret);
        $result = $client->report('769835449,1093175430');
        $this->assertTrue($result->isOk === true);
    }
} 