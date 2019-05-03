<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldRealms extends JFormFieldText
{
    public $type = 'Realms';

    protected function getSuggestions()
    {
        $realms = array();

        if (!class_exists('WoW'))
        {
            return $realms;
        }

        try
        {
            $result = WoW::getInstance()->getAdapter('WoWAPI')->getData('realms', true);
        } catch (Exception $e)
        {
            return $realms;
        }

        foreach ($result->body->realms as $key => $realm)
        {
            $realms[$key] = new stdClass;
            $realms[$key]->name = $realm->slug;
            $realms[$key]->value = $realm->name;
        }

        return $realms;
    }
}