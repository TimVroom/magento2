<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Centinel
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml sales order create validation card block
 *
 * @category   Magento
 * @package    Magento_Centinel
 * @author      Magento Core Team <core@magentocommerce.com>
 */

namespace Magento\Centinel\Block\Adminhtml\Validation;

class Form extends \Magento\Sales\Block\Adminhtml\Order\Create\AbstractCreate
{
    /**
     * Prepare validation and template parameters
     */
    protected function _toHtml()
    {
        $payment = $this->getQuote()->getPayment();
        if ($payment && $method = $payment->getMethodInstance()) {
            if ($method->getIsCentinelValidationEnabled() && $centinel = $method->getCentinelValidator()) {
                $this->setFrameUrl($centinel->getValidatePaymentDataUrl())
                    ->setContainerId('centinel_authenticate_iframe')
                    ->setMethodCode($method->getCode())
                ;
                return parent::_toHtml();
            }
        }
        return '';
    }
}

