<?php
namespace test;

use model;

class UserTest extends AppTest
{

    public function tearDown(){
        $this->truncate('User');
        parent::tearDown();
    }

    public function testInsert()
    {
        $user = $this->buildData();
        $this->em->persist($user);
        $this->em->flush();
        $this->assertEquals($user->getId(), 1);
    }

    public function testSelect()
    {
        $user = $this->buildData();
        $this->em->persist($user);
        $this->em->flush();
        $newUser = $this->em->find('model\User', 1);
        $this->assertEquals($user->getId(), $newUser->getId());
        $this->assertEquals($user->getName(), $newUser->getName());
    }

    public function testUpdate()
    {
        $user = $this->buildData();
        $this->em->persist($user);
        $this->em->flush();
        $newUser = $this->em->find('model\User', 1);
        $this->assertEquals($user->getId(), $newUser->getId());
        $this->assertEquals($user->getName(), $newUser->getName());
        $newUser->setName('Alterado');
        $this->em->persist($newUser);
        $this->em->flush();
        $newUser = $this->em->find('model\User', 1);
        $this->assertEquals($user->getId(), $newUser->getId());
        $this->assertEquals($newUser->getName(), 'Alterado');
    }

    public function testDelete()
    {
        $user = $this->buildData();
        $this->em->persist($user);
        $this->em->flush();
        $newUser = $this->em->find('model\User', 1);
        $this->assertEquals($user->getId(), $newUser->getId());
        $this->assertEquals($user->getName(), $newUser->getName());
        $this->em->remove($newUser);
        $this->em->flush();
        $newUser = $this->em->find('model\User', 1);
        $this->assertNull($newUser);
    }

    private function buildData()
    {
        $user = new model\User();
        $user->setName('Jack Daniels');
        $user->setLogin('jack');
        $user->setPassword(md5('pass'));
        $user->setEmail('jack@daniels.com');
        $user->setType(1);

        return $user;
    }

}