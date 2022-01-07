<?php

namespace Sga\Smtp\Controller\Adminhtml\Smtp;

use Psr\Log\LoggerInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\Template\SenderResolver;
use Magento\Framework\App\Area;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;
use Sga\Smtp\Helper\Config as ConfigHelper;
use Sga\Smtp\Model\Mail;

class Test extends Action
{
    const ADMIN_RESOURCE = 'Sga_Smtp::config';

    protected $_logger;
    protected $_configHelper;
    protected $_mail;
    protected $_transportBuilder;
    protected $_senderResolver;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        ConfigHelper $configHelper,
        Mail $mail,
        TransportBuilder $transportBuilder,
        SenderResolver $senderResolver
    ) {
        $this->_logger = $logger;
        $this->_configHelper = $configHelper;
        $this->_mail = $mail;
        $this->_transportBuilder = $transportBuilder;
        $this->_senderResolver = $senderResolver;

        parent::__construct($context);
    }

    public function execute()
    {
        $result = ['status' => false];

        $params = $this->getRequest()->getParams();
        if ($params && $params['to']) {
            $config = [
                'type' => 'smtp',
                'host' => $params['host'],
                'auth' => $params['authentication'],
                'username' => $params['username'],
                'ignore_log' => true,
                'force_sent' => true
            ];

            if ($params['protocol']) {
                $config['ssl'] = $params['protocol'];
            }
            if ($params['port']) {
                $config['port'] = $params['port'];
            }
            if ($params['password'] === '******') {
                $config['password'] = $this->_configHelper->getSmtpPassword();
            } else {
                $config['password'] = $params['password'];
            }
            if ($params['return_path']) {
                $config['return_path'] = $params['return_path'];
            }

            $this->_mail->setSmtpOptions(Store::DEFAULT_STORE_ID, $config);

            $from = $this->_senderResolver->resolve(
                isset($params['from']) ? $params['from'] : $config['username'],
            );

            $this->_transportBuilder
                ->setTemplateIdentifier('sga_smtp_test_email_template')
                ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => Store::DEFAULT_STORE_ID])
                ->setTemplateVars([])
                ->setFrom($from)
                ->addTo($params['to']);

            try {
                $this->_transportBuilder->getTransport()->sendMessage();

                $result = [
                    'status'  => true,
                    'content' => __('Sent successfully!')
                ];
            } catch (Exception $e) {
                $result['content'] = $e->getMessage();
                $this->_logger->critical($e);
            }
        } else {
            $result['content'] = __('Test Error');
        }

        return $this->getResponse()->representJson(json_encode($result));
    }
}
