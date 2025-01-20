<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkflowEvents.
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\Entity(repositoryClass="Webkul\UVDesk\AutpmationBundle\Repository\WorkflowEventsRepository")
 *
 * @ORM\Table(name="uv_workflow_events")
 */
class WorkflowEvents
{
    /**
     * @var int
     *
     * @ORM\Id()
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $eventId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=191)
     */
    private $event;

    /**
     * @var Workflow
     *
     * @ORM\ManyToOne(targetEntity="Webkul\UVDesk\AutomationBundle\Entity\Workflow", inversedBy="WorkflowEvents")
     *
     * @ORM\JoinColumn(name="workflow_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $workflow;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set eventId.
     *
     * @param int $eventId
     *
     * @return WorkflowEvents
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Get eventId.
     *
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set event.
     *
     * @param string $event
     *
     * @return WorkflowEvents
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event.
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set workflow.
     *
     * @return WorkflowEvents
     */
    public function setWorkflow(?Workflow $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow.
     *
     * @return Workflow
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }
}
