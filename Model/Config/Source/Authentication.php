<?php

namespace Sga\Smtp\Model\Config\Source;

use \Magento\Framework\Data\OptionSourceInterface;

class Authentication implements OptionSourceInterface
{
    public function toOptionArray()
    {
        $options = [
            [
                'value' => 'smtp',
                'label' => __('NONE')
            ],
            [
                'value' => 'plain',
                'label' => __('PLAIN')
            ],
            [
                'value' => 'login',
                'label' => __('LOGIN')
            ],
            [
                'value' => 'crammd5',
                'label' => __('CRAM-MD5')
            ],
        ];

        return $options;
    }
}
