<?php

namespace Etailors\Forms\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tableName = $installer->getTable('etailors_forms_form');
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'form_id',
                    Table::TYPE_INTEGER,
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
                    'store_ids',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => '0'],
                    'Store Ids'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Form Title'
                )
                ->addColumn(
                    'treat_pages_as_sections',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Are pages really sections?'
                )
                ->addColumn(
                    'template',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Form template'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated At'
                )
                
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        
        $tableName = $installer->getTable('etailors_forms_page');
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'page_id',
                    Table::TYPE_INTEGER,
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
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'etailors_forms_form.form_id'
                )
                ->addColumn(
                    'store_ids',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => '0'],
                    'Store Ids'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Form Title'
                )
                ->addColumn(
                    'template',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Form template'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'default' => '0'],
                    'Sorting'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated At'
                )
                ->addForeignKey(
                    $installer->getFkName('etailors_forms_page', 'form_id', 'etailors_forms_form', 'form_id'),
                    'form_id',
                    $installer->getTable('etailors_forms_form'),
                    'form_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        
        $tableName = $installer->getTable('etailors_forms_field');
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'field_id',
                    Table::TYPE_INTEGER,
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
                    'page_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'etailors_forms_page.page_id'
                )
                ->addColumn(
                    'store_ids',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => '0'],
                    'Store Ids'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Field Title'
                )
                ->addColumn(
                    'type',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Field type'
                )
                ->addColumn(
                    'is_required',
                    Table::TYPE_INTEGER,
                    1,
                    ['nullable' => false, 'default' => '0'],
                    'Is field required'
                )
                ->addColumn(
                    'validation',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Validation type'
                )
                ->addColumn(
                    'options',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Options for select,radio,checkbox etc'
                )
                ->addColumn(
                    'contains_email',
                    Table::TYPE_INTEGER,
                    1,
                    ['nullable' => false, 'default' => '0'],
                    'Does field contain user email?'
                )
                ->addColumn(
                    'template',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => ''],
                    'Form template'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'default' => '0'],
                    'Sorting'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated At'
                )
                ->addForeignKey(
                    $installer->getFkName('etailors_forms_field', 'page_id', 'etailors_forms_page', 'page_id'),
                    'page_id',
                    $installer->getTable('etailors_forms_page'),
                    'page_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        
        /** Container to add all answers to one person **/
        $tableName = $installer->getTable('etailors_forms_email');
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'email_id',
                    Table::TYPE_INTEGER,
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
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => '0'],
                    'Email address'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated At'
                )
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        
        $tableName = $installer->getTable('etailors_forms_answer');
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'answer_id',
                    Table::TYPE_INTEGER,
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
                    'email_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'etailors_forms_email.email_id'
                )
                ->addColumn(
                    'field_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'unsigned' => true, 'default' => '0'],
                    'etailors_forms_field.field_id'
                )
                ->addColumn(
                    'answer',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Answer'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated At'
                )
                
                ->addForeignKey(
                    $installer->getFkName('etailors_forms_answer', 'email_id', 'etailors_forms_email', 'email_id'),
                    'email_id',
                    $installer->getTable('etailors_forms_email'),
                    'email_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('etailors_forms_answer', 'field_id', 'etailors_forms_field', 'field_id'),
                    'field_id',
                    $installer->getTable('etailors_forms_field'),
                    'field_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        
        $installer->endSetup();
    }
}
