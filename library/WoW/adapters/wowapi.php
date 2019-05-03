<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class WoWAdapterWoWAPI extends WoWAdapterAbstract
{
    public function getData($target, $persistent = false)
    {
        $uri = new JUri;

        switch ($target)
        {
            case 'guild':
            case 'members':
            case 'achievements':
                $uri->setPath('/wow/guild/' . rawurlencode($this->params->get('realm')) . '/' . rawurlencode($this->params->get('guild')));
                $uri->setVar('fields', 'members,achievements');
                break;

            case 'races':
                $uri->setPath('/wow/data/character/races');
                break;

            case 'classes':
                $uri->setPath('/wow/data/character/classes');
                break;

            case 'realms':
                $uri->setPath('/wow/realm/status');
                break;

            default:
                return null;
                break;
        }

        return $this->getRemote($uri, $persistent);
    }

    protected function getAchievement($achievement, $persistent = true)
    {
        $uri = new JUri;
        $uri->setPath('/wow/achievement/' . (int)$achievement);

        return $this->getRemote($uri, $persistent);
    }

    protected function getMember($member, $realm, array $fields = array('items', 'achievements'))
    {
        $uri = new JUri;
        $uri->setPath('/wow/character/' . $realm . '/' . $member);
        $uri->setVar('fields', implode(',', $fields));

        return $this->getRemote($uri);
    }

    /**
     * @param JUri $uri
     * @param bool $persistent
     *
     * @return mixed
     */
    protected function getRemote($uri, $persistent = false)
    {
        $uri->setScheme($this->params->get('scheme', 'https'));
        $uri->setHost($this->params->get('region') . '.api.battle.net');
        $uri->setVar('locale', $this->params->get('locale'));
        $uri->setVar('apikey', $this->params->get('apikey'));

        $this->url = $uri->toString();

        $result = parent::getRemote($this->url, $persistent);

        $result->body = json_decode($result->body);

        if ($result->code != 200)
        {
            // hide api key from normal users
            if (!JFactory::getUser()->get('isRoot'))
            {
                $uri->delVar('apikey');
                $this->url = $uri->toString();
            }
            $msg = JText::sprintf('Server Error: %s url: %s', $result->body->reason, JHtml::_('link', $this->url, $result->code, array('target' => '_blank'))); // TODO JText::_()
            throw new RuntimeException($msg);
        }

        return $result;
    }
}