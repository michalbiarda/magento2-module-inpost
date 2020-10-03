<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddLockerColumnsToQuoteAndOrder implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $setup;

    /**
     * @param SchemaSetupInterface $setup
     */
    public function __construct(SchemaSetupInterface $setup)
    {
        $this->setup = $setup;
    }

    /**
     * @return $this
     */
    public function apply(): self
    {
        $tables = ['quote', 'sales_order'];
        $columns = ['mb_inpost_locker_name', 'mb_inpost_locker_address_line_1', 'mb_inpost_locker_address_line_2'];
        foreach ($tables as $table) {
            $tableName = $this->setup->getTable($table);
            foreach ($columns as $column) {
                $this->setup->getConnection()
                    ->addColumn(
                        $tableName,
                        $column,
                        [
                            'type' => Table::TYPE_TEXT,
                            'length' => 255,
                            'comment' => ucwords(str_replace('_', ' ', $column))
                        ]
                    );
            }
        }
        return $this;
    }

    /**
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
