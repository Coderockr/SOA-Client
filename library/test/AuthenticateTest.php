<?php
namespace test;

use service,
    model;

class AuthenticateTest extends AppTest
{

    public function tearDown(){
        $this->truncate('User');
        parent::tearDown();
    }

    public function testExecute()
    {
        $user   = $this->buildUser();
        
        $params = array(
        );
        
        $service = new service\Authenticate();
        $result = $service->execute($params);
        $this->assertEquals($result['status'], 'error');
        $this->assertEquals($result['data'], 'Missing parameters');

        $params = array(
            'login'     => 'invalid user',
            'password'  => 'invalid password'
        );
        
        $service = new service\Authenticate();
        $result = $service->execute($params);
        $this->assertEquals($result['status'], 'error');
        $this->assertEquals($result['data'], 'User not found');

        $params = array(
            'login'     => 'jack',
            'password'  => 'invalid password'
        );
        
        $result = $service->execute($params);
        $this->assertEquals($result['status'], 'error');
        $this->assertEquals($result['data'], 'Invalid password');

        $params = array(
            'login'     => 'jack',
            'password'  => md5('pass')
        );
        
        $result = $service->execute($params);

        $serializer = new service\Serializer();
        $this->assertEquals($result['status'], 'success');
        $this->assertEquals($result['data'], $serializer->serialize($user, 'json'));
    }

    private function buildUser()
    {
        $user = new model\User();
        $user->setName('Jack Daniels');
        $user->setLogin('jack');
        $user->setPassword(md5('pass'));
        $user->setEmail('jack@daniels.com');
        $user->setType(1);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }     

}