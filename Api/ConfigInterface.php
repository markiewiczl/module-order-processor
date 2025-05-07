<?php

namespace Markiewiczl\OrderProcessor\Api;

interface ConfigInterface
{
    /**
     * @return bool
     */
    public function isEnabledOrderProcessing(): bool;

    /**
     * @return string
     */
    public function getCommentText(): string;
}
