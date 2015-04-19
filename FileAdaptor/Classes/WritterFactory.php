<?php
/**
 * User: Cristian Bitoi
 * Date: 4/17/15
 */
namespace FileAdaptor\Classes;

class WritterFactory
{
    /**
     * Method for returning writter based on writter type
     *
     * @param string $type
     * @param array $options writter options
     * @return WritterAbstract
     * @throws \Exception - if factory wasn't able to create a writter based on type
     */
    public static function build($type, $options = array())
    {
        $writter = null;

        switch($type) {
            case 'xml':
                $writter = new Writters\XmlWritter($options);
                break;
            case 'html':
                $writter = new Writters\HtmlWritter($options);
                break;
        }

        if(!($writter instanceof WritterAbstract)) {
            trigger_error('WritterFactory should return a Writter. Valid types: xml, html.', E_USER_ERROR);
        }

        return $writter;
    }
}