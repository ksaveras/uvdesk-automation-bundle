<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Webkul\UVDesk\AutomationBundle\Entity\Workflow;

/**
 * WorkflowRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkflowRepository extends \Doctrine\ORM\EntityRepository
{
    public $safeFields = ['page', 'limit', 'sort', 'order', 'direction'];
    public const LIMIT = 10;

    public function getEventWorkflows($eventName, $isActive = true, $isPredefined = true)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('workflow')
            ->from(Workflow::class, 'workflow')
            ->leftJoin('workflow.workflowEvents', 'workflowEvents')
            ->where('workflow.status = :status')->setParameter('status', $isActive)
            ->andWhere('workflow.isPredefind = :isPredefined')->setParameter('isPredefined', $isPredefined)
            ->andWhere('workflowEvents.event = :eventType')->setParameter('eventType', $eventName)
            ->orderBy('workflow.sortOrder', Criteria::ASC)
            ->getQuery()->getResult();
    }

    public function getWorkflows(?\Symfony\Component\HttpFoundation\ParameterBag $obj = null, $container)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('w.id, w.name, w.status')->from($this->getEntityName(), 'w')
            ->orderBy('w.sortOrder', Criteria::ASC);

        return ['workflows' => $qb->getQuery()->getArrayResult()];
    }
}
