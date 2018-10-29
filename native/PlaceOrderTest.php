<?php

//silent notice;
ini_set('error_reporting', E_ALL & ~E_NOTICE);

class PlaceOrderTest extends PHPUnit_Extensions_Selenium2TestCase
{

    protected $js_delay=2;

    /**
     * Variable to specify which browsers to run the tests on
     * @var array
     */
    public static $browsers = [
        ['browserName' => 'firefox'],
        ['browserName' => 'chrome'],
        ['browserName' => 'iexplorer']
    ];

    public function __construct()
    {
        $this->setHost('127.0.0.1');
        $this->setPort(4444);
        $this->setSeleniumServerRequestsTimeout(60);
        $this->setDesiredCapabilities([]);
    }

    protected function setUp()
    {
        $this->setBrowserUrl('http://m2.magento.loc/');
    }

    public function test_HomePage()
    {
        $this->url('index.php');
        //check title;
        $this->assertEquals('Home page', $this->title());

        //check Logo image
        $this->assertEquals(true,$this->byXPath('/html/body/div/header/div[2]/strong/img')->displayed());

        //check Category1 link
        $category_element=$this->byXPath('//*[@id="ui-id-4"]')->text();
        $this->assertContains('Category',$category_element);

        //test Category
        $category_url='category-1.html';
        $this->check_Category($category_url);
    }

    private function check_Category($category_url)
    {
        $this->url($category_url);
        //check title
        $this->assertEquals('Category 1',$this->title());

        //check Simple Product link
        $this->assertContains('Simple Product',$this->byXPath('//*[@id="maincontent"]/div[3]/div[1]/div[2]/ol/li[5]/div/div/strong/a')->text());

        //check form_key
        $form_key = $this->byName('form_key')->value();
        $this->assertRegExp('/\w+/',$this->byName('form_key')->value());

        $product_url='simple-product-10500.html';
        $this->check_Simple_Product_10500($product_url);
    }

    private function check_Simple_Product_10500($product_url)
    {
        $this->url($product_url);
        //check title
        $this->assertEquals('Simple Product 10500',$this->title());

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
        $add_to_cart_url = $product_addtocart_url;
        $this->check_Add_To_Cart($add_to_cart_url);
    }

    private function check_Add_To_Cart($add_to_cart_url)
    {
        //product add to cart check
        $this->url($add_to_cart_url);
        //check title
        $this->assertEquals('Shopping Cart',$this->title());

        //check success message on "add to cart"
        $added_success_notice=$this->byXPath('//*[@id="maincontent"]/div[2]/div/div/div')->text();
        $this->assertRegExp('/You\ added\ .*\ to\ your\ shopping\ cart/',$added_success_notice);

        //click on button "proceed to check out"
        $this->byXPath('//*[@id="maincontent"]/div[3]/div/div/div/ul/li[1]/button')->click();
        $this->assertEquals('Checkout',$this->title());

        //check "Checkout as Guest" radio button
        sleep($this->js_delay-1);
        $this->assertEquals(true,$this->byXPath('//*[@id="login:guest"]')->displayed());
        $this->byXPath('//*[@id="login:guest"]')->click();
        sleep($this->js_delay-1);
        $this->assertEquals(true,$this->byXPath('//*[@id="login:guest"]')->selected());

        //Guest checkout click continue
        $this->assertEquals(true,$this->byXPath('//*[@id="onepage-guest-register-button"]')->displayed());
        $this->byXPath('//*[@id="onepage-guest-register-button"]')->click();
        sleep($this->js_delay);

        //input billing info
        $this->byXPath('//*[@id="billing:firstname"]')->value('Jon');
        $this->byXPath('//*[@id="billing:lastname"]')->value('Dou');
        $this->byXPath('//*[@id="billing:email"]')->value('gergund@gmail.com');
        $this->byXPath('//*[@id="billing:street1"]')->value('10 Somestreet str');
        $this->byXPath('//*[@id="billing:city"]')->value('Somecity');
        $this->byXPath('//*[@id="billing:region_id"]')->value('Alabama');
        $this->byXPath('//*[@id="billing:postcode"]')->value('342342');
        $this->byXPath('//*[@id="billing:country_id"]')->value('United States');
        $this->byXPath('//*[@id="billing:telephone"]')->value('345345345453');

        //check Ship to this address
        $this->byXPath('//*[@id="billing:use_for_shipping_yes"]')->click();
        sleep($this->js_delay);

        //Billing click continue
        $this->byXPath('//*[@id="billing-buttons-container"]/div/button')->click();
        sleep($this->js_delay);

        //Shipping click continue
        $this->byXPath('//*[@id="shipping-method-buttons-container"]/div[1]/button')->click();
        sleep($this->js_delay);

        //Payment click continue
        $this->byXPath('//*[@id="payment-buttons-container"]/div[1]/button')->click();
        sleep($this->js_delay);

        //Place order click
        $this->byXPath('//*[@id="review-buttons-container"]/div[1]/button')->click();
        sleep($this->js_delay+1);

        //Check place order success message
        $this->byXPath('//*[@id="maincontent"]/div[1]/h1/span')->text();
        $this->assertContains('Thank you for your purchase!',$this->byXPath('//*[@id="maincontent"]/div[1]/h1/span')->text());
    }
    
}
?>