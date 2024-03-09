<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2023 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @build-date      2023/11/27
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Authentication\Authentication;
use Joomla\CMS\User\User;

jimport('sourcecoast.plugins.jfbconnectPlugin');

/**
 * Facebook Authentication Plugin
 */
class plgAuthenticationJFBConnectAuth extends JFBConnectPlugin
{
    var $configModel;

    function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function onUserAuthenticate($credentials, $options, &$response)
    {
        if($this->isLoaded())
        {
            $response->type = 'JFBConnectAuth';

            # authentication via facebook for Joomla always uses the FB API and secret keys
            # When this is present, the user's FB uid is used to look up their Joomla uid and log that user in
            jimport('joomla.filesystem.file');
            $provider = null;
            if (isset($options['provider']))
                $provider = $options['provider'];

            if (class_exists('JFBCFactory') && $provider)
            {
                # always check the secret username and password to indicate this is a JFBConnect login
                #echo "Entering JFBConnectAuth<br>";
                if (($credentials['username'] != $provider->appId) ||
                    ($credentials['password'] != $provider->secretKey)
                )
                {
                    $response->status = Authentication::STATUS_FAILURE;
                    return false;
                }

                #echo "Passed API/Secret key check, this is a FB login<br>";
                include_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/models/usermap.php');
                $userMapModel = new JFBConnectModelUserMap();

                $providerUserId = $provider->getProviderUserId();
                $app = Factory::getApplication();

                #echo "Facebook user = ".$fbUserId;
                # test if user is logged into Facebook
                if ($providerUserId)
                {
                    # Test if user has a Joomla mapping
                    $jUserId = $userMapModel->getJoomlaUserId($providerUserId, $provider->systemName);
                    if ($jUserId)
                    {
                        $jUser = User::getInstance($jUserId);
                        if ($jUser->id == null) // Usermapping is wrong (likely, user was deleted)
                        {
                            $userMapModel->deleteMapping($providerUserId, $provider->systemName);
                            return false;
                        }

                        if ($jUser->block)
                        {
                            $isAllowed = false;
                            JFBCFactory::log(Text::_('JERROR_NOLOGIN_BLOCKED'), 'error');
                        }
                        else
                        {
                            PluginHelper::importPlugin('socialprofiles');
                            $args = array($provider->systemName, $jUserId, $providerUserId);
                            $responses = $app->triggerEvent('onSocialProfilesOnAuthenticate', $args);
                            $isAllowed = true;
                            foreach ($responses as $prResponse)
                            {
                                if (is_object($prResponse) && !$prResponse->status)
                                {
                                    $isAllowed = false;
                                    JFBCFactory::log($prResponse->message, 'error');
                                }
                            }
                        }

                        if ($isAllowed)
                        {
                            $response->status = Authentication::STATUS_SUCCESS;
                            $response->username = $jUser->username;
                            $response->language = $jUser->getParam('language');
                            $response->email = $jUser->email;
                            $response->fullname = $jUser->name;
                            $response->error_message = '';
                            return true;
                        }
                    }

                }
            }
        }
        # catch everything else as an authentication failure
        $response->status = Authentication::STATUS_FAILURE;
        return false;
    }

}
