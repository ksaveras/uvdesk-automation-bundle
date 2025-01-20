<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Workflow;

abstract class FunctionalGroup
{
    public const USER = 'user';
    public const AGENT = 'agent';
    public const TICKET = 'ticket';
    public const CUSTOMER = 'customer';
}
