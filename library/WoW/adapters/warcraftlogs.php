<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class WoWAdapterWarcraftLogs extends WoWAdapterAbstract
{
    /**
     * @see https://www.warcraftlogs.com/v1/docs#!/Reports/
     * @throws RuntimeException
     *
     * @return stdClass
     */
    public function getData($api_key)
    {
        $uri = new JUri;

        $this->params->set('realm', str_replace("'", '', $this->params->get('realm')));
        $this->params->set('guild', str_replace(' ', '+', $this->params->get('guild')));

        $uri->setPath('/v1/reports/guild/' . $this->params->get('guild') . '/' . JApplicationHelper::stringURLSafe($this->params->get('realm')) . '/' . $this->params->get('region'));
        $uri->setVar('api_key', $api_key);

        return $this->getRemote($uri);
    }

    /**
     * @param JUri $uri
     *
     * @throws RuntimeException
     *
     * @return mixed
     */
    protected function getRemote($uri, $persistent = false)
    {
        $uri->setScheme('https');
        $uri->setHost('www.warcraftlogs.com');

        $this->url = $uri->toString();

        $result = parent::getRemote($this->url, $persistent);

        $result->body = json_decode($result->body);

        if ($result->code != 200)
        {
            // hide api key from normal users
            if (!JFactory::getUser()->get('isRoot'))
            {
                $uri->delVar('api_key');
                $this->url = $uri->toString();
            }
            $msg = JText::sprintf('Server Error: %s url: %s', $result->body->error, JHtml::_('link', $this->url, $result->code, array('target' => '_blank'))); // TODO JText::_()
            throw new RuntimeException($msg);
        }

        return $result;
    }
}