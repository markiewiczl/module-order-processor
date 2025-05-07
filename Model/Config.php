<?php

declare(strict_types=1);

namespace Markiewiczl\OrderProcessor\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Markiewiczl\OrderProcessor\Api\ConfigInterface;

class Config implements ConfigInterface
{
    private const IS_ENABLED_ORDER_PROCESSING_CONFIG_PATH
        = 'markiewiczl_order_processor_config/general_settings/is_enabled_order_processing';

    private const COMMENT_TEXT_CONFIG_PATH = 'markiewiczl_order_processor_config/general_settings/comment_text';

    /**
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        private readonly ScopeConfigInterface $config
    ) {
    }

    /**
     * @return bool
     */
    public function isEnabledOrderProcessing(): bool
    {
        return $this->config->isSetFlag(self::IS_ENABLED_ORDER_PROCESSING_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getCommentText(): string
    {
        return $this->config->getValue(self::COMMENT_TEXT_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}
