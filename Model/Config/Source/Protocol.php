<?php

namespace Sga\Smtp\Model\Config\Source;

use \Magento\Framework\Data\OptionSourceInterface;

class Protocol implements OptionSourceInterface
{
    public function toOptionArray()
    {
        $options = [
            [
                'value' => '',
                'label' => __('None')
            ],
            [
                'value' => 'ssl',
                'label' => __('SSL')
            ],
            [
                'value' => 'tls',
                'label' => __('TLS')
            ],
        ];

        return $options;
    }
}
