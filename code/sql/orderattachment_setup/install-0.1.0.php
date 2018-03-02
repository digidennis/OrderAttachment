<?php

$installer = $this;
$installer->startSetup();

$slottable = $installer->getConnection()->newTable( $installer->getTable('digidennis_orderattachment/orderattachment'))
    ->addColumn('orderattachment_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true), 'Attachment Id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false), 'Order Id')
    ->addColumn('datablob', Varien_Db_Ddl_Table::TYPE_TEXT, 0, array(
        'unsigned' => true,
        'nullable' => true), 'Data Blob');
$installer->getConnection()->createTable($slottable);
$installer->endSetup();
