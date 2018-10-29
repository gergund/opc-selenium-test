<?php

//silent notice;
ini_set('error_reporting', E_ALL & ~E_NOTICE);

//define('BROWSERSTACK_USER', 'mrd3');
//define('BROWSERSTACK_KEY', 'XcNXT4ywB52PcyEcUtSy');

class BaseCase extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $js_delay=3;

    /**
     * Variable to specify which browsers to run the tests on
     * @var array
     */
    public static $browsers = array(
        ['browserName' => 'chrome'],/*
        ['browserName' => 'firefox'],
        ['browserName' => 'iexplorer']
        /* array(
            'browserName' => 'safari',
            'host' => 'hub.browserstack.com',
            'port' => 80,
            'desiredCapabilities' => array(
                'version' => '8',
                'browserstack.user' => BROWSERSTACK_USER,
                'browserstack.key' => BROWSERSTACK_KEY,
                'os' => 'OS X',
                'os_version' => 'Yosemite'
            )
        ),
        array(
            'browserName' => 'chrome',
            'host' => 'hub.browserstack.com',
            'port' => 80,
            'desiredCapabilities' => array(
                'version' => '39',
                'browserstack.user' => BROWSERSTACK_USER,
                'browserstack.key' => BROWSERSTACK_KEY,
                'os' => 'Windows',
                'os_version' => '8.1',
                "resolution" => "1920x1080"
            )
        ),
        array(
            'browserName' => 'firefox',
            'host' => 'hub.browserstack.com',
            'port' => 80,
            'desiredCapabilities' => array(
                'version' => '35',
                'browserstack.user' => BROWSERSTACK_USER,
                'browserstack.key' => BROWSERSTACK_KEY,
                'os' => 'Windows',
                'os_version' => '8.1',
                "resolution" => "1920x1080"
            )
        ),
        array(
            'browserName' => 'IE',
            'host' => 'hub.browserstack.com',
            'port' => 80,
            'desiredCapabilities' => array(
                'version' => '9',
                'browserstack.user' => BROWSERSTACK_USER,
                'browserstack.key' => BROWSERSTACK_KEY,
                'os' => 'Windows',
                'os_version' => '7'
            )
        ),
        array(
            'browserName' => 'iPhone',
            'host' => 'hub.browserstack.com',
            'port' => 80,
            'desiredCapabilities' => array(
                'browserstack.user' => BROWSERSTACK_USER,
                'browserstack.key' => BROWSERSTACK_KEY,
                'platform' => 'MAC',
                'browserName' => 'iPhone',
                'device' => 'iPhone 5'
            )
        ),
        array(
            'browserName' => 'iPad',
            'host' => 'hub.browserstack.com',
            'port' => 80,
            'desiredCapabilities' => array(
                'browserstack.user' => BROWSERSTACK_USER,
                'browserstack.key' => BROWSERSTACK_KEY,
                'platform' => 'MAC',
                'browserName' => 'android',
                'device' => 'iPad Air'
            )
        ),
        array(
            'browserName' => 'android',
            'host' => 'hub.browserstack.com',
            'port' => 80,
            'desiredCapabilities' => array(
                'browserstack.user' => BROWSERSTACK_USER,
                'browserstack.key' => BROWSERSTACK_KEY,
                'platform' => 'ANDROID',
                'browserName' => 'android',
                'device' => 'Samsung Galaxy S5'
                )
        ) */
        );

    public function __construct()
    {
        //local Selenium Grid settings
        $this->setHost('127.0.0.1');
        $this->setPort(4444);

        $this->setSeleniumServerRequestsTimeout(90);
        $this->setDesiredCapabilities([]);
    }

    protected function setUp()
    {
        //$this->setBrowserUrl('http://opc.itwnik.com/');
        $this->setBrowserUrl('http://m2.magento.loc/');
    }
}