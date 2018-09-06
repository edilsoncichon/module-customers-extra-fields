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
namespace Cichon\CustomerExtraFields\Block\Adminhtml\Tab;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

/**
 * Customer account form block
 */
class ExtraFields extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'tab/extrafields.phtml';

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        AccountManagementInterface $customerAccountManagement,
        array $data = []
    ) {
        $this->_subscriberFactory = $subscriberFactory;
        $this->customerAccountManagement = $customerAccountManagement;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Return Tab label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Extra Fields');
    }

    /**
     * Return Tab title
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Extra Fields');
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Initialize the form.
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        /**@var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('_extrafields');
        $customerId = $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
//        $subscriber = $this->_subscriberFactory->create()->loadByCustomerId($customerId);
//        $this->_coreRegistry->register('subscriber', $subscriber, true);

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Extra Fields Configuration')]);

        $fieldset->addField(
            'cpf',
            'text',
            [
                'label' => __('CPF'),
                'name' => 'cpf',
                'data-form-part' => $this->getData('target_form'), //todo ?
                'onchange' => 'this.value = this.checked;' //todo ?
            ]
        );

        $fieldset->addField(
            'rg',
            'text',
            [
                'label' => __('RG'),
                'name' => 'RG',
                'value' => '3466604',
                'data-form-part' => $this->getData('target_form'), //todo ?
                'onchange' => 'this.value = this.checked;' //todo ?
            ]
        );

        if ($this->customerAccountManagement->isReadonly($customerId)) {
            $form->getElement('cpf')->setReadonly(true, true);
        }
//        $isSubscribed = $subscriber->isSubscribed();
//        $form->setValues(['subscription' => $isSubscribed ? 'true' : 'false']);
//        $form->getElement('subscription')->setIsChecked($isSubscribed);

        $this->updateFromSession($form, $customerId);

        $this->setForm($form);
        return $this;
    }

    /**
     * Update form elements from session data
     *
     * @param \Magento\Framework\Data\Form $form
     * @param int $customerId
     * @return void
     */
    protected function updateFromSession(\Magento\Framework\Data\Form $form, $customerId)
    {
        $data = $this->_backendSession->getCustomerFormData();
        if (!empty($data)) {
            $dataCustomerId = isset($data['customer']['entity_id']) ? $data['customer']['entity_id'] : null;
            if (isset($data['subscription']) && $dataCustomerId == $customerId) {
                $form->getElement('subscription')->setIsChecked($data['subscription']);
            }
        }
    }

    /**
     * Retrieve the date when the subscriber status changed.
     *
     * @return null|string
     */
    public function getStatusChangedDate()
    {
        $subscriber = $this->_coreRegistry->registry('subscriber');
        if ($subscriber->getChangeStatusAt()) {
            return $this->formatDate(
                $subscriber->getChangeStatusAt(),
                \IntlDateFormatter::MEDIUM,
                true
            );
        }

        return null;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
}
