<?php

namespace service;

use model;

class Authenticate extends Service
{
    public function execute($parameters = array()) {
        $required = array('login', 'password');
        if (!$this->checkParameters($parameters, $required)) {
            $result = array(
                'status' => 'error', 
                'data' => 'Missing parameters'
            );
            return $result;
        }

        $em = $this->getEntityManager();
        try {
            $user = $em->getRepository('model\User')->findBy(array('login' => $parameters['login']));
            if (!$user) {
                $result = array(
                    'status' => 'error', 
                    'data' => 'User not found'
                );
                return $result;
            }

            if ($user[0]->getPassword() != md5($parameters['password'])) {
                $result = array(
                    'status' => 'error', 
                    'data' => 'Invalid password'
                );
                return $result;
            }
            
        }
        catch(Exception $e) {
            $result = array(
                'status' => 'error', 
                'data' => $e->getMessage()
            );
            return $result;
        }
        $serializer = new Serializer();

        $result = array(
            'status' => 'success', 
            'data' => $serializer->serialize($user[0], 'json')
        );
        return $result;

    }

       
}