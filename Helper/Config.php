<?php
namespace Sga\Smtp\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_PATH_SMTP_ENABLED = 'system/smtp/smtp/enabled';
    const XML_PATH_SMTP_LOG_EMAIL = 'system/smtp/smtp/log_email';
    const XML_PATH_SMTP_HOST = 'system/smtp/smtp/host';
    const XML_PATH_SMTP_PORT = 'system/smtp/smtp/port';
    const XML_PATH_SMTP_PROTOCOL = 'system/smtp/smtp/protocol';
    const XML_PATH_SMTP_AUTHENTICATION = 'system/smtp/smtp/authentication';
    const XML_PATH_SMTP_USERNAME = 'system/smtp/smtp/username';
    const XML_PATH_SMTP_PASSWORD = 'system/smtp/smtp/password';
    const XML_PATH_SMTP_RETURN_PATH_EMAIL = 'system/smtp/smtp/return_path_email';

    const XML_PATH_SMTP_TEST_FROM = 'system/smtp/test_email/from';
    const XML_PATH_SMTP_TEST_TO = 'system/smtp/test_email/to';

    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SMTP_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function logEmailEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SMTP_LOG_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpHost($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_HOST,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpPort($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_PORT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpProtocol($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_PROTOCOL,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpAuthentication($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_AUTHENTICATION,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpUsername($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_USERNAME,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpPassword($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_PASSWORD,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpReturnPathEmail($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_RETURN_PATH_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpTestFrom($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_TEST_FROM,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getSmtpTestTo($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SMTP_TEST_TO,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
