<?php

namespace Magelearn\Salesorder\Block\Item;

use Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer as DefaultRenderer;

class Renderer extends DefaultRenderer
{
    /**
     * Magento string lib
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

    /**
     * @var \Magento\Catalog\Model\Product\OptionFactory
     */
    protected $_productOptionFactory;

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $imageBuilder;
	
	protected $_productImageHelper;
	
	protected $_productRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Catalog\Helper\Image $productImageHelper,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        array $data = []
    ) {
        $this->imageBuilder = $imageBuilder;
		$this->_productImageHelper = $productImageHelper;
		$this->_productRepository = $productRepository;
        parent::__construct($context, $string, $productOptionFactory, $data);
    }

    /**
     * Get item product
     *
     * @return \Magento\Catalog\Model\Product
     * @codeCoverageIgnore
     */
    public function getProduct()
    {
        return $this->getItem()->getProduct();
    }

    /**
     * Identify the product from which thumbnail should be taken.
     *
     * @return \Magento\Catalog\Model\Product
     * @codeCoverageIgnore
     */
    public function getProductForThumbnail()
    {
        return $this->getProduct();
    }

    /**
     * Retrieve product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
	/**
     * Schedule resize of the image
     * $width *or* $height can be null - in this case, lacking dimension will be calculated.
     *
     * @see \Magento\Catalog\Model\Product\Image
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resizeImage($product, $imageId, $width, $height = null)
    {
        $resizedImage = $this->_productImageHelper
                           ->init($product, $imageId)
                           ->constrainOnly(TRUE)
                           ->keepAspectRatio(TRUE)
                           ->keepTransparency(TRUE)
                           ->keepFrame(FALSE)
                           ->resize($width, $height);
        return $resizedImage;
    }
	
	public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    
    public function getProductBySku($sku)
    {
        return $this->_productRepository->get($sku);
    }
}