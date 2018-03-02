<?php

class Digidennis_OrderAttachment_Block_Adminhtml_Order_View_Tab_Contents extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('digidennis/orderattachment/attachment.phtml');

    }

    public function getTabLabel() {
        return $this->__('Order Attachments');
    }

    public function getTabTitle() {
        return $this->__('Order Attachments');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder(){
        return Mage::registry('current_order');
    }
}