define([
    "jquery",
    "Magento_Ui/js/modal/alert",
    "mage/translate",
    "jquery/ui"
], function ($, alert, $t) {
    "use strict";

    $.widget('mage.smtpTestEmail', {
        options: {},

        _create: function () {
            var self = this;

            $('#system_smtp_test_email_sent').click(function (e) {
                e.preventDefault();
                if (self.element.val()) {
                    self._ajaxSubmit();
                }
            });
        },

        _ajaxSubmit: function () {
            $.ajax({
                url: this.options.url,
                data: {
                    from: $('#system_smtp_test_email_from').val(),
                    to: $('#system_smtp_test_email_to').val(),
                    host: $('#system_smtp_smtp_host').val(),
                    port: $('#system_smtp_smtp_port').val(),
                    protocol: $('#system_smtp_smtp_protocol').val(),
                    authentication: $('#system_smtp_smtp_authentication').val(),
                    username: $('#system_smtp_smtp_username').val(),
                    password: $('#system_smtp_smtp_password').val(),
                    return_path: $('#system_smtp_smtp_return_path_email').val(),
                },
                dataType: 'json',
                showLoader: true,
                success: function (result) {
                    alert({
                        title: result.status ? $t('Success') : $t('Error'),
                        content: result.content
                    });
                }
            });
        }
    });

    return $.mage.smtpTestEmail;
});
