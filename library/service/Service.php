<?php

namespace service;

abstract class Service
{
    private $em;
    /**
     * Construct
     * 
     * @param mixed $em
     */
    public function __construct($em = null)
    {
        if (is_null($em)) {
            include __DIR__.'/../../bootstrap.php';
            $this->em = $em;
        }
        else {
            $this->em = $em;
        }
    }

    /**
     * Execute the Service
     * 
     * @param array $param
     * @return array 
     */
    abstract public function execute($parameters = array());

    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Verify required parameters
     * 
     * @param array $parameters
     * @param array $required
     * @return boolean 
     */
    protected function checkParameters($parameters, $required) {
        foreach($required as $r) {
            if (!isset($parameters[$r])) {
                return false;
            }
        }
        return true;
    }

    protected function debug($message)
    {
        $result = array(
            'status' => 'error', 
            'data' => $message
        );
        return $result;
    }
}
