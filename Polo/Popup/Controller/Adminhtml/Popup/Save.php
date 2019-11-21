<?php

namespace Polo\Popup\Controller\Adminhtml\Popup;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{


    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    protected $_pubDirectory;
    protected $_fileUploaderFactory;
    protected $import;

    protected $collectionFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Polo\Popup\Model\ResourceModel\Post\CollectionFactory $collectionFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->collectionFactory = $collectionFactory;
        $this->_pubDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::PUB);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Polo_Popup::save');
    }

    /**
     * Save Cron Jobs
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {

        try {
            $id = $this->getRequest()->getParam('post_id');
            $pid = $this->getRequest()->getParams();
            $newproductid = $pid['product_id'];

            $collection = $this->collectionFactory->create();
            foreach ($collection as $collect)
            {
                $productid = $collect->getProductId();

                if ($productid == $newproductid)
                {
                    $this->messageManager->addError(__('This Product Id Already Exist'));
                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/index', ['_secure' => $this->getRequest()->isSecure()]
                    );
                }
                else
                {

                }

            }
            /** @var \Polo\Popup\Model\Post $model */
            $model = $this->_objectManager->create('Polo\Popup\Model\Post');
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addError(__('PopUp is missing.'));

                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/index', ['_secure' => $this->getRequest()->isSecure()]
                    );
                }

            }

            $data = $this->getRequest()->getParams();
            unset($data['key']);

            $target = $this->_pubDirectory->getAbsolutePath('media/popup_image/');
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'file']);

            /** Allowed extension types */
            $uploader->setAllowedExtensions(['png','jpg']);

            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($target);
            if ($result['file']) {
                $this->messageManager->addSuccess(__('File has been successfully uploaded'));
            }

            $fileFullPath = $result['path'] . $result['file'];
            if (file_exists($fileFullPath)) {
                $jsonString = file_get_contents($fileFullPath);
                $importData = json_decode($jsonString, true);
            }else{
                $importData = [];
            }

            $data['file'] = str_replace($this->_pubDirectory->getAbsolutePath(), '' , $result['path']). $result['file'];
            $model->setData($data);
            $model->save();

        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath(
            '*/*/index', ['_secure' => $this->getRequest()->isSecure()]
        );
    }
}