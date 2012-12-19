<?php
$loader = require_once __DIR__.'/vendor/autoload.php';
$loader->add('model', __DIR__ . '/library');
$loader->add('service', __DIR__ . '/library');
$loader->add('test', __DIR__ . '/library');

use Doctrine\ORM\Tools\Setup,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\EventManager as EventManager,
    Doctrine\ORM\Events,
    Doctrine\ORM\Configuration,
    Doctrine\Common\Cache\ApcCache as Cache,
    Doctrine\Common\Annotations\AnnotationRegistry, 
    Doctrine\Common\Annotations\AnnotationReader,
    DMS\Filter\Mapping,
    DMS\Filter\Filter,
    Doctrine\Common\ClassLoader;

if(!getenv('APPLICATION_ENV')) 
    $env = 'testing';
else
    $env = getenv('APPLICATION_ENV');

if ($env == 'testing')
    include __DIR__.'/configs/configs.testing.php';
elseif ($env == 'development')
    include __DIR__.'/configs/configs.development.php';
else
    include __DIR__.'/configs/configs.php';

//filter
//Get Doctrine Reader
$reader = new AnnotationReader();
//$reader->setEnableParsePhpImports(true);

//Load AnnotationLoader
$loader = new Mapping\Loader\AnnotationLoader($reader);

//Get a MetadataFactory
$metadataFactory = new Mapping\ClassMetadataFactory($loader);

//Get a Filter
$filter = new Filter($metadataFactory);

//doctrine
$config = new Configuration();
$cache = new Cache();
$config->setQueryCacheImpl($cache);
$config->setProxyDir('/tmp');
$config->setProxyNamespace('EntityProxy');
$config->setAutoGenerateProxyClasses(true);
 
//mapping (example uses annotations, could be any of XML/YAML or plain PHP)
AnnotationRegistry::registerFile(__DIR__. DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'doctrine' . DIRECTORY_SEPARATOR . 'orm' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Doctrine' . DIRECTORY_SEPARATOR . 'ORM' . DIRECTORY_SEPARATOR . 'Mapping' . DIRECTORY_SEPARATOR . 'Driver' . DIRECTORY_SEPARATOR . 'DoctrineAnnotations.php');
AnnotationRegistry::registerAutoloadNamespace('JMS', __DIR__. DIRECTORY_SEPARATOR . 'vendor/jms/serializer-bundle/');
AnnotationRegistry::registerAutoloadNamespace('DMS', __DIR__. DIRECTORY_SEPARATOR . 'vendor');

$driver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
    new Doctrine\Common\Annotations\AnnotationReader(),
    array(__DIR__ .'/library/model')
);
$config->setMetadataDriverImpl($driver);
$config->setMetadataCacheImpl($cache);

//getting the EntityManager
$em = EntityManager::create(
    $dbOptions,
    $config
);

//load subscribers
$evm = $em->getEventManager();
try {
    $directoryIterator = new \DirectoryIterator(__DIR__ . '/library/model/subscriber');
    foreach ($directoryIterator as $f) {
        if ($f->getFileName() != '.' && $f->getFilename() !='..') {
            $subscriber = 'model\\subscriber\\' . $f->getBasename('.php');
            $evm->addEventSubscriber(new $subscriber);
        }
    }
}catch (UnexpectedValueException $e) {
    //directory doesn't exists
}