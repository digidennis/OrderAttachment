<?php


class Digidennis_OrderAttachment_Model_Resource_Orderattachment_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_orderattachment/orderattachment');
    }
}