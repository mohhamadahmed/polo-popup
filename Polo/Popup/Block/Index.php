<?php


namespace Polo\Popup\Block;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $_productRepository;

    protected $_registry;

    protected $_product;

    protected $_postfactory;

    protected $modelFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductFactory $modelFactory,
        \Magento\Catalog\Model\Product $product,
        \Polo\Popup\Model\PostFactory  $postFactory,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_postfactory = $postFactory;
        $this->_product=$product;
        $this->_productRepository = $productRepository;
        $this->_modelFactory = $modelFactory;
        parent::__construct($context, $data);
    }

    public function getCollection(){

        return $this->_modelFactory->create()->getCollection();
    }

    public function getpostCollection(){

        return $this->_postfactory->create()->getCollection();
    }

    public function getProductById($id)
    {
        return $this->_postfactory->create()->load($id,'Product_Id');
    }

    public function getProductBySku($sku)
    {
        return $this->_productRepository->get($sku);
    }
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

}