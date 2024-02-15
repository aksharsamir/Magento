<?php

namespace Etailors\Forms\Setup;
 
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $table = $setup->getTable('etailors_forms_form');
            
            $setup->getConnection()->addColumn(
                $table,
                'form_code',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'Unique code'
                ]
            );
        }
        
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $table = $setup->getTable('etailors_forms_email');
            
            $setup->getConnection()->addColumn(
                $table,
                'form_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 11,
                    'unique' => false,
                    'nullable' => false,
                    'comment' => 'Form Id'
                ]
            );
        }
        
        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $table = $setup->getTable('etailors_forms_form');
            
            $setup->getConnection()->addColumn(
                $table,
                'thank_you_page_content',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => null,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'Thank you page content'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'admin_email_email',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'Admin email to address'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'admin_email_name',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'Admin email to name'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'admin_email_subject',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'Admin email subject'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'admin_email_content',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => null,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'Admin email content'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'user_email_enabled',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 1,
                    'unique' => false,
                    'nullable' => false,
                    'comment' => 'Is user email enabled'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'user_email_email',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'User email from addres'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'user_email_name',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'User email from name'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'user_email_subject',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'User email subject'
                ]
            );
            
            $setup->getConnection()->addColumn(
                $table,
                'user_email_content',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => null,
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'User email content'
                ]
            );
            
            $table = $setup->getTable('etailors_forms_email');
            
            $setup->getConnection()->addColumn(
                $table,
                'store_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'store.store_id'
                ]
            );
            
            $tableName = $setup->getTable('etailors_forms_form_store');
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'form_store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'form_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'etailors_forms_form.form_id'
                )
                ->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'stores.store_id'
                );
             $setup->getConnection()->createTable($table);
        }
        
        if (version_compare($context->getVersion(), '2.0.0') < 0) {
            $table = $setup->getTable('etailors_forms_field');
            $setup->getConnection()->addColumn(
                $table,
                'display_in_overview',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'unique' => true,
                    'nullable' => false,
                    'comment' => 'Should answer be shown in overview'
                ]
            );
        }
        $setup->endSetup();
    }
}
