<?php
/**
 * Created by PhpStorm.
 * User: dgergun
 * Date: 23.03.15
 * Time: 15:44
 */

require_once __DIR__.'/../code/BaseCase.php';

class SimplePlaceOrderTest extends BaseCase
{
    public $product_url = "/sprite-foam-roller.html";
    public $product_title = "Sprite Foam Roller";

    public function test()
    {
        $this->url($this->product_url);
        sleep($this->js_delay);

        //check title
        $this->assertContains($this->product_title,$this->title());

        //check form_key
        $form_key = $this->byName('form_key')->value();
        $this->assertRegExp('/\w+/',$form_key);

        //check product_id
        $product_id = $this->byName('product')->value();
        $this->assertRegExp('/\d+/',$product_id);

        //check add to cart url
        $product_addtocart_url = $this->byXPath('//*[@id="product_addtocart_form"]')->attribute('action');
        $this->assertRegExp('/\/checkout\/cart\/add\/uenc\/\w+\/product\/\d+\//',$product_addtocart_url);

        //check jQuery version
        $script = array('script' => 'return window.jQuery.fn.jquery;', 'args' => array());
        $this->assertContains("1.11.0",$this->execute($script));

        //click add to cart: native click
        $this->byXPath('//*[@id="product-addtocart-button"]')->click();
        sleep($this->js_delay*2);

        //check success message on "add to cart"
        $added_success_notice=$this->byXPath('//*[@id="maincontent"]/div[2]/div[2]/div/div')->text();
        sleep($this->js_delay*2);
        $this->assertRegExp('/You\ added\ .*\ to\ your\ shopping\ cart/',$added_success_notice);

        $this->Shopping_Cart();
    }


    private function Shopping_Cart()
    {
        //go to checkout
        $this->url('/checkout/cart');
        //wait to load
        sleep($this->js_delay);

       //check title
        $this->assertEquals('Shopping Cart',$this->title());

       //check product in cart
        $this->assertContains($this->product_title,$this->byXPath('//*[@id="shopping-cart-table"]/tbody/tr[1]/td[1]/div/strong/a')->text());
        $this->byXPath('//*[@id="maincontent"]/div[3]/div/div/div[1]/ul/li[2]/button')->click();
        sleep($this->js_delay);

        $this->Shipping_info();
    }

    private function Shipping_info()
    {
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:firstname"]')->value('John');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:lastname"]')->value('Smith');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:email"]')->value('john.smith@gmail.com');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:telephone"]')->value('5551234567');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:street1"]')->value('10 Main street');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:postcode"]')->value('12345');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:city"]')->value('Somecity');
        sleep($this->js_delay);
        $this->byXPath('//*[@id="shipping:region_id"]')->value('Colorado');
        sleep($this->js_delay);

        //$this->byXPath('//*[@id="shipping:country_id"]')->value('US');
        //$this->byXPath('//*[@id="shipping_same_as_billing"]')->click();

        //set Shipping same as Billing
        $this->execute(array('script' => "window.jQuery('#shipping_same_as_billing').prop('checked',1);", 'args' => array()));
        $sameasBilling = $this->execute(array('script' => "return window.jQuery('#shipping_same_as_billing').prop('checked');", 'args' => array()));
        $this->assertEquals(true,$sameasBilling);

        sleep($this->js_delay);


        $this->execute(array('script' => "window.jQuery('#shipping\\:country_id').trigger('mouseleave');", 'args' => array()));

        //$this->execute(array('script' => "window.jQuery('#shipping\\:telephone').val('5551234567');", 'args' => array()));
        //$this->execute(array('script' => "window.jQuery('#shipping\\:telephone').mask('(999) 999-9999');", 'args' => array()));
        //$this->execute(array('script' => "window.jQuery('#shipping\\:telephone').trigger('mouseleave');", 'args' => array()));
        //$this->execute(array('script' => "window.jQuery('#shipping\\:region_id').val('13');", 'args' => array()));
        //$this->execute(array('script' => "window.jQuery('#shipping\\:region_id').trigger('mouseleave');", 'args' => array()));
        sleep($this->js_delay);

        //check enabled Shipping methods
        $shipping_method_check = array('script' => "return window.jQuery('#shipping_method-load input').length", 'args' => array());
        $this->assertGreaterThan(0,$this->execute($shipping_method_check));

        sleep($this->js_delay);

        //select Shipping Method: Fixed(Flat Rate)
        $this->execute(array('script' => "window.jQuery('#s_method_flatrate_flatrate').prop('checked',1);", 'args' => array()));
        $this->execute(array('script' => "window.jQuery('#s_method_flatrate_flatrate').trigger('mouseleave')", 'args' => array()));

        //$this->byXPath('//*[@id="s_method_flatrate_flatrate"]')->click();
        sleep($this->js_delay);

        $this->Place_Order();
    }

    private function Place_Order ()
    {
        $this->byXPath('//*[@id="opc-orderplace-button"]')->click();

        sleep($this->js_delay*3);

        //successful place order check
        $success_placeorder = $this->byXPath('//*[@id="maincontent"]/div[1]/h1/span')->text();
        $this->assertContains('Thank you for your purchase!', $success_placeorder);

        file_put_contents('C:\tmp\screen-PlaceOrder'.date('YmdHis').'.png',$this->currentScreenshot());
    }
}