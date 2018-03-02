<?php


class Digidennis_OrderAttachment_Adminhtml_OrderattachmentController extends Mage_Adminhtml_Controller_Action
{
    public function imageuploadAction()
    {
        $type = 'qqfile';
        $jsondata = array(
            'success' => true,
        );

        if(isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '' && $this->getRequest()->getParam('order_id')) {
            try {
                $uploader = new Varien_File_Uploader($type);
                $uploader->setAllowedExtensions(['jpg', 'png', 'jpeg']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
                $attachment = Mage::getModel('digidennis_orderattachment/orderattachment')->load( $this->getRequest()->getParam('order_id'), 'order_id');

                if( !$attachment->getOrderattachmentId()){
                    $attachment->setOrderId($this->getRequest()->getParam('order_id'));
                }
                $uploader->save($path, $_FILES[$type]['name'] );
                $object = array(
                    'uuid' => $this->getRequest()->getParams()['qquuid'],
                    'name' => $this->getRequest()->getParams()['qqfilename'],
                    'size' => $this->getRequest()->getParams()['qqtotalfilesize'],
                    'path' => $uploader->getUploadedFileName(),
                );
                $mediafiles = unserialize($attachment->getDatablob());
                if(is_null($mediafiles) || !is_array($mediafiles))
                    $mediafiles = array();
                $mediafiles[] = $object;
                $attachment->setDatablob(serialize($mediafiles));
                $attachment->save();

            } catch (Exception $e) {
                $jsondata['success'] = false;
                $jsondata['message'] = $e->getMessage();
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($jsondata));
    }

    public function imagedeleteAction()
    {
        $uuid = $this->getRequest()->getParam('qquuid');
        $attachment = Mage::getModel('digidennis_orderattachment/orderattachment')->load($this->getRequest()->getParam('order_id'), 'order_id');
        if($uuid && $attachment->getOrderattachmentId()){
            $mediafiles = unserialize($attachment->getDatablob());
            $keep = array();
            $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
            foreach ($mediafiles as $file){
                if( $file['uuid'] === $uuid ){
                    unlink($path . $file['path']);
                } else {
                    $keep[] = $file;
                }
            }
            $attachment->setDatablob(serialize($keep));
            $attachment->save();
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode(['success'=>true]));
    }

    public function imageinitAction()
    {
        $jsondata = array();
        if( $this->getRequest()->getParam('order_id') ){
            $attachment = Mage::getModel('digidennis_orderattachment/orderattachment')->load($this->getRequest()->getParam('order_id'), 'order_id');
            if( $attachment->getOrderattachmentId() ){
                $mediafiles = unserialize($attachment->getDatablob());
                foreach ($mediafiles as $file){
                    $object = new StdClass();
                    $object->name = $file['name'];
                    $object->uuid = $file['uuid'];
                    $object->size = $file['size'];
                    $jsondata[] = $object;
                }
            }
        };
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($jsondata));
    }
}