<?php

class Digidennis_OrderAttachment_Model_Resource_Orderattachment extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('digidennis_orderattachment/orderattachment', 'orderattachment_id');
    }
}