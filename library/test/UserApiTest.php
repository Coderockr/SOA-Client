<?php
namespace test;

use model;

class UserApiTest extends ApiTest
{

    public function __construct() 
    {
        parent::__construct();
        $this->curl_url = $this->url . '/user';
    }
    
    public function tearDown(){
        $this->truncate('User');
        parent::tearDown();
    }

    public function testNoData()
    {
        $result = $this->get($this->curl_url);
        $this->assertEquals($result['code'], 404);
        $this->assertEquals($result['type'], "text/json; charset=UTF-8");
    }

    public function testPost()
    {
        
        $fields = $this->buildData();
        $result = $this->post($this->curl_url, $fields);
        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['type'], "text/json; charset=UTF-8");
       
    }

    public function testInvalidPost()
    {
       $fields = array(
            'user[name]'=>''
        );

        $result = $this->post($this->curl_url, $fields);
        $this->assertEquals($result['code'], 400);
        $this->assertEquals($result['type'], "text/json; charset=UTF-8");
    }

    public function testGet()
    {
        $fields = $this->buildData();
        $client = $this->post($this->curl_url, $fields);

        $result = $this->get($this->curl_url . '/' . $client['response']->id);
        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['type'], "text/json; charset=UTF-8");
        $this->assertEquals(count($result['response']), 1);
        $this->assertEquals($result['response']->name, 'Jack Daniels');
    }

    public function testPut()
    {
        $fields = $this->buildData();

        //create
        $result = $this->post($this->curl_url, $fields);
        
        //update
        $fields = array(
            'user[name]'  => 'Jim Beam',
            'user[login]' =>'jim',
            'user[password]' => md5('pass'),
            'user[type]' => 2
        );
        
        $result = $this->put($this->curl_url . '/' . $result['response']->id, $fields);

        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['type'], "text/json; charset=UTF-8");
        $this->assertEquals($result['response']->name, 'Jim Beam');
    }    

    public function testDelete()
    {
        $fields = $this->buildData();

        //create
        $result = $this->post($this->curl_url, $fields);
        $url = $this->curl_url . '/' . $result['response']->id;
        $result = $this->delete($url);
        
        $this->assertEquals($result['code'], 200);
        $this->assertEquals($result['type'], "text/json; charset=UTF-8");

        $result = $this->get($url);
        $this->assertEquals($result['code'], 404);
    } 

    private function buildData()
    {

        $fields = array(
            'user[name]' =>'Jack Daniels',
            'user[login]' =>'jack',
            'user[password]' => md5('pass'),
            'user[email]' =>'jack@daniels.com',
            'user[type]' => 1
        );
    
        return $fields;
    }

}