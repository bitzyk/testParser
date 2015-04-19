<?php
require_once __DIR__ . '/../../../FileAdaptor/config.php';


class WritterFactoryTest extends PHPUnit_Framework_TestCase
{

    public function testWritterFactoryReturnWritter()
    {
        $writter = FileAdaptor\Classes\WritterFactory::build('xml');;
        $this->assertInstanceOf('FileAdaptor\Classes\Writters\XmlWritter', $writter);
    }


}