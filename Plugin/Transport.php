<?php
namespace Sga\Smtp\Plugin;

use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\TransportInterface as Subject;
use Magento\Framework\Phrase;
use Sga\Smtp\Helper\Config as ConfigHelper;
use Sga\Smtp\Model\Mail;

class Transport
{
    protected $_logger;
    protected $_storeId;
    protected $_mail;
    protected $_configHelper;

    public function __construct(
        LoggerInterface $logger,
        Mail $mail,
        ConfigHelper $configHelper
    ) {
        $this->_logger = $logger;
        $this->_mail = $mail;
        $this->_configHelper = $configHelper;
    }

    public function aroundSendMessage(
        Subject $subject,
        \Closure $proceed
    ) {
        $this->_storeId = $this->_mail->getStoreId();
        $message = $this->_getMessage($subject);
        if ($this->_configHelper->isEnabled()) {
            $transport = $this->_mail->getTransport($this->_storeId);
            $message = $this->_mail->processMessage($message, $this->_storeId);
            try {
                $transport->send($message);
                $this->_logEmail($message);
            } catch (\Exception $e) {
                $this->_logEmail($message, false);
                throw new MailException(new Phrase($e->getMessage()), $e);
            }
        } else {
            $proceed();
        }
    }

    protected function _getMessage($transport)
    {
        try {
            $message = $transport->getMessage();

            $reflectionClass = new \ReflectionClass($message);
            $zendMessage = $reflectionClass->getProperty('zendMessage');
            $zendMessage->setAccessible(true);
            $message = $zendMessage->getValue($message);
        } catch (\Exception $e) {
            return null;
        }

        return $message;
    }

    protected function _logEmail($message, $sent = true)
    {
        if ($this->_configHelper->logEmailEnabled()) {
            $this->_logger->info('-- send email start --');
            $this->_logger->info('- sent : '.(int)$sent);
            $this->_logger->info('- subject : '.$message->getSubject());

            $emails = [];
            $addresses = $message->getTo();
            $addresses->rewind();
            for ($i = 0; $i < $addresses->count(); $i++) {
                $address = $addresses->current();
                $emails[] = $address->getEmail();

                $addresses->next();
            }
            $this->_logger->info('- to : '.implode(', ', $emails));

            $this->_logger->info('- message : '.$message->getBody()->generateMessage());
            $this->_logger->info('-- send email end --');
        }
    }
}
