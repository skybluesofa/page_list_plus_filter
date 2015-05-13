<?php
namespace Concrete\Package\SkybluesofaPageListPlusFilter\Attribute\PageListPlusAttribute;

use \Concrete\Core\Attribute\DefaultController as DefaultAttributeController;
use Loader;

/*
 * This class is really just a duplicate of the text attribute. We're just presenting it
 * here to show how the attribute and filters interact.
 */
class Controller extends DefaultAttributeController
{

    protected $searchIndexFieldDefinition = array(
        'type' => 'text',
        'options' => array(
            'length' => 4294967295,
            'default' => null,
            'notnull' => false
        )
    );

    public function form()
    {
        if (is_object($this->attributeValue)) {
            $value = Loader::helper('text')->entities($this->getAttributeValue()->getValue());
        }
        print Loader::helper('form')->text($this->field('value'), $value);
    }

    public function composer()
    {
        if (is_object($this->attributeValue)) {
            $value = Loader::helper('text')->entities($this->getAttributeValue()->getValue());
        }
        print Loader::helper('form')->text($this->field('value'), $value, array('class' => 'span5'));
    }
}
