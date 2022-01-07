<?php

namespace Sga\Smtp\Plugin;

use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder as Subject;
use Sga\Smtp\Model\Mail;

class TemplateTransportBuilder
{
    protected $_mail;
    protected $_senderResolver;

    public function __construct(
        Mail $mail,
        SenderResolverInterface $senderResolver
    ) {
        $this->_mail = $mail;
        $this->_senderResolver = $senderResolver;
    }

    public function beforeSetTemplateOptions(
        Subject $subject,
        $templateOptions
    ) {
        if (array_key_exists('store', $templateOptions)) {
            $this->_mail->setStoreId($templateOptions['store']);
        }

        return [$templateOptions];
    }
}
