<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Controller\Automations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webkul\UVDesk\AutomationBundle\Entity;
use Webkul\UVDesk\AutomationBundle\EventListener\WorkflowListener;
use Webkul\UVDesk\CoreFrameworkBundle\Services\TicketService;
use Webkul\UVDesk\CoreFrameworkBundle\Services\UserService;

final class WorkflowXHR extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly WorkflowListener $workflowListenerService,
        private readonly TicketService $ticketService,
        private readonly TranslatorInterface $translator,
    ) {}

    public function workflowsListXhr(Request $request, ContainerInterface $container): Response
    {
        if (!$this->userService->isAccessAuthorized('ROLE_AGENT_MANAGE_WORKFLOW_AUTOMATIC')) {
            return $this->redirect($this->generateUrl('helpdesk_member_dashboard'));
        }

        $repository = $this->getDoctrine()->getRepository(Entity\Workflow::class);
        $json = $repository->getWorkflows($request->query, $container);

        return new JsonResponse($json);
    }

    public function WorkflowsxhrAction(Request $request): Response
    {
        if (!$this->userService->isAccessAuthorized('ROLE_AGENT_MANAGE_WORKFLOW_AUTOMATIC')) {
            return $this->redirect($this->generateUrl('helpdesk_member_dashboard'));
        }

        $json = [];
        $error = false;
        if ($request->isXmlHttpRequest()) {
            if ('POST' === $request->getMethod()) {
                $em = $this->getDoctrine()->getManager();
                // sort order update
                $workflows = $em->getRepository(Entity\Workflow::class)->findAll();

                $sortOrders = $request->request->get('orders');
                if (\count($workflows)) {
                    foreach ($workflows as $id => $workflow) {
                        if (!empty($sortOrders[$workflow->getId()])) {
                            $workflow->setSortOrder($sortOrders[$workflow->getId()]);
                            $em->persist($workflow);
                        } else {
                            $error = true;

                            break;
                        }
                    }
                    $em->flush();
                }
                if (!$error) {
                    $json['alertClass'] = 'success';
                    $json['alertMessage'] = $this->translator->trans('Success! Order has been updated successfully.');
                }
            } elseif ('DELETE' === $request->getMethod()) {
                // $this->isAuthorized(self::ROLE_REQUIRED_AUTO);

                $em = $this->getDoctrine()->getManager();
                $id = $request->attributes->get('id');
                // $workFlow = $this->getWorkflow($id, 'Events');
                $workFlow = $em->getRepository(Entity\Workflow::class)
                    ->findOneBy(['id' => $id]);

                if (!empty($workFlow)) {
                    $em->remove($workFlow);
                    $em->flush();
                } else {
                    $error = true;
                }

                if (!$error) {
                    $json['alertClass'] = 'success';
                    $json['alertMessage'] = $this->translator->trans('Success! Workflow has been removed successfully.');
                }
            }
        }
        if ($error) {
            $json['alertClass'] = 'danger';
            $json['alertMessage'] = $this->translator->trans('Warning! You are not allowed to perform this action.');
        }

        return new JsonResponse($json);
    }

    public function getWorkflowConditionOptionsXHR($entity, Request $request): Response
    {
        $error = false;
        $json = $results = [];
        $supportedConditions = ['TicketPriority', 'TicketType', 'TicketStatus', 'source', 'agent', 'group', 'team', 'agent_name', 'agent_email', 'stage'];

        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }
        if ('GET' !== $request->getMethod() || !\in_array($entity, $supportedConditions)) {
            throw $this->createNotFoundException();
        }

        switch ($entity) {
            case 'team':
                $json = json_encode($this->userService->getSupportTeams());

                break;
            case 'group':
                $json = $this->userService->getSupportGroups();

                break;
            case 'stage':
                $json = $this->get('task.service')->getStages();

                break;
            case 'TicketType':
                $json = $this->ticketService->getTypes();

                break;
            case 'agent':
            case 'agent_name':
                $defaultAgent = ['id' => 'actionPerformingAgent', 'name' => 'Action Performing Agent'];
                $agentList = $this->userService->getAgentPartialDataCollection();
                $agentList[] = $defaultAgent;

                $json = json_encode(array_map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'name' => $item['name'],
                    ];
                }, $agentList));

                break;
            case 'agent_email':
                $json = json_encode(array_map(function ($item) {
                    return [
                        'id' => $result['id'],
                        'name' => $result['email'],
                    ];
                }, $this->userService->getAgentsPartialDetails()));

                break;
            case 'source':
                $allSources = $this->ticketService->getAllSources();
                $results = [];
                foreach ($allSources as $key => $source) {
                    $results[] = [
                        'id' => $key,
                        'name' => $source,
                    ];
                }
                $json = json_encode($results);
                $results = [];

                break;
            case 'TicketStatus':
            case 'TicketPriority':
                $json = json_encode(array_map(function ($item) {
                    return [
                        'id' => $item->getId(),
                        'name' => $item->getCode(),
                    ];
                }, $this->getDoctrine()->getRepository('Webkul\\UVDesk\\CoreFrameworkBundle\\Entity\\'.ucfirst($entity))->findAll()));

                break;
            default:
                $json = [];

                break;
        }

        // if (!empty($results)) {
        //     $ignoredArray = ['__initializer__', '__cloner__', '__isInitialized__', 'description', 'color', 'company', 'createdAt', 'users', 'isActive'];
        //     $json = $this->getSerializeObj($ignoredArray)->serialize($results, 'json');
        // }

        return new JsonResponse($json);
    }

    public function getWorkflowActionOptionsXHR($entity, Request $request, ContainerInterface $container): Response
    {
        foreach ($this->workflowListenerService->getRegisteredWorkflowActions() as $workflowAction) {
            if ($workflowAction->getId() == $entity) {
                $options = $workflowAction->getOptions($container);

                if (!empty($options)) {
                    return new JsonResponse($options);
                }

                break;
            }
        }

        return new JsonResponse(
            [
                'alertClass' => 'danger',
                'alertMessage' => 'Warning! You are not allowed to perform this action.',
            ],
        );
    }
}
