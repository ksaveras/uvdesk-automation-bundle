<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Workflow;

abstract class Event
{
    public static function getId()
    {
        return null;
    }

    public static function getDescription()
    {
        return null;
    }

    public static function getFunctionalGroup()
    {
        return null;
    }
}
