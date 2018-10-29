<?php

require_once __DIR__.'/../code/BaseCase.php';

class SimplePlaceOrderTest extends BaseCase
{
    public $product_url = "/sprite-foam-roller.html";
    public $product_title = "Sprite Foam Roller";

    public function test()
    {
        $this->url($this->product_url);
        //check title
        $this->assertEquals($this->product_title,$this->title());

        //check form_key
        $form_key = $this->byName('form_key')->value();
        $this->assertRegExp('/\w+/',$form_key);

        //check product_id
        $product_id = $this->byName('product')->value();
        $this->assertRegExp('/\d+/',$product_id);

        //check add to cart url
        $product_addtocart_url = $this->byXPath('//*[@id="product_addtocart_form"]')->attribute('action');
        $this->assertRegExp('/\/checkout\/cart\/add\/uenc\/\w+,,\/product\/\d+\//',$product_addtocart_url);

        //click add to cart url
        $this->Add_To_Cart($product_addtocart_url);
    }

    private function Add_To_Cart($add_to_cart_url)
    {
        //product add to cart check
        $this->url($add_to_cart_url);
        //check title
        $this->assertEquals('Shopping Cart',$this->title());

        //check success message on "add to cart"
        $added_success_notice=$this->byXPath('//*[@id="maincontent"]/div[2]/div/div/div')->text();
        $this->assertRegExp('/You\ added\ .*\ to\ your\ shopping\ cart/',$added_success_notice);
        $this->Checkout();
    }

    private function Checkout()
    {
        //go to checkout
        $this->byXPath('//*[@id="maincontent"]/div[3]/div/div/div[1]/ul/li[2]/button')->click();
        $this->assertEquals('Checkout',$this->title());
        $this->Shipping_info();
    }

    private function Shipping_info()
    {
        $this->byXPath('//*[@id="shipping_same_as_billing"]')->click();
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:firstname"]')->value('John');
        $this->byXPath('//*[@id="shipping:lastname"]')->value('Smith');
        $this->byXPath('//*[@id="shipping:street1"]')->value('10 Main street');
        $this->byXPath('//*[@id="shipping:city"]')->value('Somecity');
        $this->byXPath('//*[@id="shipping:region_id"]')->value('Alabama');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:postcode"]')->value('90602');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:country_id"]')->value('United States');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:telephone"]')->value('5551234567');
        sleep($this->js_delay);
        $this->Place_Order();
    }

    /* private function Billing_info()
    {
        //input billing info
        $this->byXPath('//*[@id="billing:firstname"]')->value('John');
        $this->byXPath('//*[@id="billing:lastname"]')->value('Smith');
        $this->byXPath('//*[@id="billing:email"]')->value('john.smith@gmail.com');
        $this->byXPath('//*[@id="billing:street1"]')->value('10 Main street');
        $this->byXPath('//*[@id="billing:city"]')->value('Somecity');
        $this->byXPath('//*[@id="billing:region_id"]')->value('Alabama');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="billing:postcode"]')->value('90602');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="billing:country_id"]')->value('United States');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="billing:telephone"]')->value('5353453453');
        sleep($this->js_delay);
        //$this->Shipping_info();
    } */



    private function Place_Order ()
    {
        $this->byXPath('//*[@id="opc-orderplace-button"]')->click();
        sleep($this->js_delay*3);
    }

}
?>