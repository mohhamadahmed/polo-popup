<?php
namespace Polo\Popup\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'polo_popup_post';

    protected $_cacheTag = 'polo_popup_post';

    protected $_eventPrefix = 'polo_popup_post';

    protected function _construct()
    {
        $this->_init('Polo\Popup\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}