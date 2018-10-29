<?php
/**
 * Created by PhpStorm.
 * User: dgergun
 * Date: 04.03.15
 * Time: 18:15
 */
require_once __DIR__.'/../code/BaseCase.php';

class GoogleTest extends BaseCase
{
    public function test()
    {
       $this->url('http://google.com');
       $this->assertContains('Google',$this->title());
    }
}