<?php

/**
 * Customers Extra Fields
 *
 * Extra Fields Brazilian for Customers module.
 *
 * @package Cichon\CustomersExtraFields
 * @author Edilson Cichon <edilsoncichon@hotmail.com>
 * @copyright Copyright (c) 2018 Edilson Cichon (http://edilson.xyz)
 * @license https://opensource.org/licenses/OSL-3.0.php Open Software License 3.0
 */

namespace Cichon\CustomerExtraFields\Setup;

use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /* @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(Customer::ENTITY, 'cpf',
            [
                'group' => 'Extra Fields',
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => __('CPF'),
                'input' => 'input',
                'class' => '',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'unique' => true,
                'apply_to' => '',
            ]
        );

        $eavSetup->addAttribute(Customer::ENTITY, 'rg',
            [
                'group' => 'Extra Fields',
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => __('RG'),
                'input' => 'input',
                'class' => '',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'unique' => true,
                'apply_to' => '',
            ]
        );
    }
}
