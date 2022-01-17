<?php

namespace Sga\Smtp\Model;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use Sga\Smtp\Helper\Config as ConfigHelper;

class Mail
{
    protected $_configHelper;

    protected $_emailLog = [];
    protected $_message;
    protected $_smtpOptions = [];
    protected $_returnPath = [];
    protected $_transport;
    protected $_storeId = 0;

    public function __construct(
        ConfigHelper $helper
    ) {
        $this->_configHelper = $helper;
    }

    public function getStoreId()
    {
        return $this->_storeId;
    }

    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    public function setSmtpOptions($storeId, $options = [])
    {
        if (isset($options['return_path'])) {
            $this->_returnPath[$storeId] = $options['return_path'];
            unset($options['return_path']);
        }

        if (isset($options['ignore_log']) && $options['ignore_log']) {
            $this->_emailLog[$storeId] = false;
            unset($options['ignore_log']);
        }

        if (isset($options['force_sent']) && $options['force_sent']) {
            unset($options['force_sent']);
        }

        if (count($options)) {
            $this->_smtpOptions[$storeId] = $options;
        }

        return $this;
    }

    public function getTransport($storeId)
    {
        if ($this->_transport === null) {
            if (!isset($this->_smtpOptions[$storeId])) {
                $options = [
                    'host' => $this->_configHelper->getSmtpHost($storeId),
                    'port' => $this->_configHelper->getSmtpPort($storeId)
                ];

                if ($this->_configHelper->getSmtpAuthentication($storeId)) {
                    $options += [
                        'auth' => $this->_configHelper->getSmtpAuthentication($storeId),
                        'username' => $this->_configHelper->getSmtpUsername($storeId),
                        'password' => $this->_configHelper->getSmtpPassword($storeId)
                    ];
                }

                if ($this->_configHelper->getSmtpProtocol($storeId)) {
                    $options['ssl'] = $this->_configHelper->getSmtpProtocol($storeId);
                }

                $this->_smtpOptions[$storeId] = $options;
            }

            $options = $this->_smtpOptions[$storeId];
            if (isset($options['auth']) && $options['auth'] !== 'smtp') {
                $options['connection_class'] = $options['auth'];
                $options['connection_config'] = [
                    'username' => $options['username'],
                    'password' => $options['password']
                ];
                unset($options['auth'], $options['username'], $options['password']);
            } else {
                unset($options['auth']);
                unset($options['username']);
                unset($options['password']);
            }
            if (isset($options['ssl'])) {
                $options['connection_config']['ssl'] = $options['ssl'];
                unset($options['ssl']);
            }
            unset($options['type']);

            $options = new SmtpOptions($options);
            $this->_transport = new Smtp($options);
        }

        return $this->_transport;
    }

    public function processMessage($message, $storeId)
    {
        if (!isset($this->_returnPath[$storeId])) {
            $this->_returnPath[$storeId] = $this->_configHelper->getSmtpReturnPathEmail($storeId);
        }

        if ($this->_returnPath[$storeId]) {
            if (method_exists($message, 'setReturnPath')) {
                $message->setReturnPath($this->_returnPath[$storeId]);
            } else {
                $message->getHeaders()->addHeaders(["Return-Path" => $this->_returnPath[$storeId]]);
            }
        }

        return $message;
    }
}
