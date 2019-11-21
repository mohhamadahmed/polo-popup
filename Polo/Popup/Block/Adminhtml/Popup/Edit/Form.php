<?php

namespace Polo\Popup\Block\Adminhtml\Popup\Edit;

/**
 * Adminhtml staff edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    const CATEGORY_ID_MARGUES = 122;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    protected $_status;

    /**
     * \Magento\Catalog\Model\CategoryFactory $_categoryFactory
     */
    protected $_categoryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Polo\Popup\Model\PostFactory  $postFactory,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_categoryFactory = $categoryFactory;
        $this->_postFactory = $postFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Polo\Popup\Model\Post $model */
//        $model = $this->_coreRegistry->registry('polo_popup');
        $model = $this->_postFactory->create();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]]
        );

        $form->setHtmlIdPrefix('polo_popup_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Image Upload'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('post_id', 'hidden', ['name' => 'post_id']);
        }


        $fieldset->addField(
            'product_id',
            'text',
            ['name' => 'product_id', 'label' => __('Product Id'), 'title' => __('Product Id'), 'required' => true]
        );

        $fieldset->addField(
            'file',
            'file',
            ['name' => 'file', 'label' => __('Image File'), 'title' => __('Image File'), 'required' => true]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);


        return parent::_prepareForm();
    }

}