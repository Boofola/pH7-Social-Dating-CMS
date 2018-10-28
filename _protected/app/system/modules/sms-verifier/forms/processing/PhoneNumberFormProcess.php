<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7cms.com>
 * @copyright      (c) 2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / SMS Verifier / Form / Processing
 */

namespace PH7;

use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Url\Header;

class PhoneNumberFormProcess extends Form
{
    public function __construct()
    {
        parent::__construct();

        $oSmsApi = SmsGatewayFactory::create($this->config->values['module.setting']['default_sms_gateway']);
        $bResult = $oSmsApi->send(
            $this->httpRequest->get('phone_number'),
            t('Your verification code is: %0% Thanks! %site_name% Team', Verification::getVerificationCode())
        );

        if ($bResult) {
            Header::redirect(
                Uri::get('sms-verifier', 'main', 'verification')
            );
        } else {
            \PFBC\Form::setError(
                'form_phone_number_verification',
                t('An error occurred while sending the verification text. Please retry.')
            );
        }
    }
}