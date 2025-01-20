<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workflow.
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\Entity(repositoryClass="Webkul\UVDesk\AutomationBundle\Repository\WorkflowRepository")
 *
 * @ORM\Table(name="uv_workflow")
 */
class Workflow
{
    /**
     * @var int
     *
     * @ORM\Id;
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=191)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $conditions;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $actions;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $isPredefind = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $status = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateUpdated;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Webkul\UVDesk\AutomationBundle\Entity\WorkflowEvents", mappedBy="workflow")
     */
    private $workflowEvents;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->workflowEvents = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name.
     *
     * @param string $name
     *
     * @return Workflow
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Workflow
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set conditions.
     *
     * @param array $conditions
     *
     * @return Workflow
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Get conditions.
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Set actions.
     *
     * @param array $actions
     *
     * @return Workflow
     */
    public function setActions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Get actions.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set sortOrder.
     *
     * @param int $sortOrder
     *
     * @return Workflow
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder.
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set isPredefind.
     *
     * @param bool $isPredefind
     *
     * @return Workflow
     */
    public function setIsPredefind($isPredefind)
    {
        $this->isPredefind = $isPredefind;

        return $this;
    }

    /**
     * Get isPredefind.
     *
     * @return bool
     */
    public function getIsPredefind()
    {
        return $this->isPredefind;
    }

    /**
     * Set status.
     *
     * @param bool $status
     *
     * @return Workflow
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateAdded.
     *
     * @param \DateTime $dateAdded
     *
     * @return Workflow
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded.
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set dateUpdated.
     *
     * @param \DateTime $dateUpdated
     *
     * @return Workflow
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated.
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Add workflowEvent.
     *
     * @return Workflow
     */
    public function addWorkflowEvent(WorkflowEvents $workflowEvent)
    {
        $this->workflowEvents[] = $workflowEvent;

        return $this;
    }

    /**
     * Remove workflowEvent.
     */
    public function removeWorkflowEvent(WorkflowEvents $workflowEvent)
    {
        $this->workflowEvents->removeElement($workflowEvent);
    }

    /**
     * Get workflowEvents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkflowEvents()
    {
        return $this->workflowEvents;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        // Add your code here
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        // Add your code here
    }
}
