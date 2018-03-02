<?php


class Digidennis_OrderAttachment_Adminhtml_ImageController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->_title($this->__('WorkSlip'))->_title($this->__('List'));
        $this->loadLayout();
        $this->_setActiveMenu('digidennis/workslipgrid');
        $this->renderLayout();
        Mage::getSingleton('adminhtml/session')->unsWorkslipEditId();
    }

    public function imageuploadAction()
    {
        $type = 'qqfile';
        $jsondata = array(
            'success' => true,
        );
        if(isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($type);
                $uploader->setAllowedExtensions(['pdf', 'jpg', 'png', 'jpeg']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
                $workslip = Mage::getModel('digidennis_workslip/workslip')->load(
                    Mage::getSingleton('adminhtml/session')->getWorkslipEditId()
                );
                if($workslip->getWorkslipId()){
                    $uploader->save($path, $_FILES[$type]['name'] );
                    $object = array(
                        'uuid' => $this->getRequest()->getParams()['qquuid'],
                        'name' => $this->getRequest()->getParams()['qqfilename'],
                        'size' => $this->getRequest()->getParams()['qqtotalfilesize'],
                        'path' => $uploader->getUploadedFileName(),
                    );
                    $mediafiles = unserialize($workslip->getMediafiles());
                    if(is_null($mediafiles) || !is_array($mediafiles))
                        $mediafiles = array();
                    $mediafiles[] = $object;
                    $workslip->setMediafiles(serialize($mediafiles));
                    $workslip->save();
                }
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
        $workslip = Mage::getModel('digidennis_workslip/workslip')->load(
            Mage::getSingleton('adminhtml/session')->getWorkslipEditId()
        );
        if($uuid && $workslip->getWorkslipId()){
            $mediafiles = unserialize($workslip->getMediafiles());
            $keep = array();
            $path = Mage::getBaseDir('media') . DS . 'uploads' . DS;
            foreach ($mediafiles as $file){
                if( $file['uuid'] === $uuid ){
                    unlink($path . $file['path']);
                } else {
                    $keep[] = $file;
                }
            }
            $workslip->setMediafiles(serialize($keep));
            $workslip->save();
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode(['success'=>true]));
    }

    public function imageinitAction()
    {
        $jsondata = array();
        if( $id = Mage::getSingleton('adminhtml/session')->getWorkslipEditId()){
            $workslip = Mage::getModel('digidennis_workslip/workslip')->load($id);
            if( $workslip->getWorkslipId() ){
                $mediafiles = unserialize($workslip->getMediafiles());
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