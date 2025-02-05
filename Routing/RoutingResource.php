<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Routing;

use Webkul\UVDesk\CoreFrameworkBundle\Definition\RoutingResourceInterface;

class RoutingResource implements RoutingResourceInterface
{
    public static function getResourcePath()
    {
        return __DIR__.'/../Resources/config/routes.yaml';
    }

    public static function getResourceType()
    {
        return RoutingResourceInterface::YAML_RESOURCE;
    }
}
