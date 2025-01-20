<?php declare(strict_types=1);

namespace Webkul\UVDesk\AutomationBundle\Controller\Automations;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webkul\UVDesk\AutomationBundle\Entity;
use Webkul\UVDesk\AutomationBundle\EventListener\PreparedResponseListener;
use Webkul\UVDesk\CoreFrameworkBundle\Services\UserService;

final class PreparedResponseXHR extends AbstractController
{
    public const ROLE_REQUIRED_MANUAL = 'ROLE_AGENT_MANAGE_WORKFLOW_MANUAL';
    public const LIMIT = 20;
    public const WORKFLOW_MANUAL = 0;
    public const WORKFLOW_AUTOMATIC = 1;
    public const NAME_LENGTH = 100;
    public const DESCRIPTION_LENGTH = 200;

    public function __construct(
        private readonly UserService $userService,
        private readonly PreparedResponseListener $preparedResponseListener,
        private readonly TranslatorInterface $translator,
    ) {}

    public function prepareResponseListXhr(Request $request, ContainerInterface $container): Response
    {
        if (!$this->userService->isAccessAuthorized('ROLE_AGENT_MANAGE_WORKFLOW_MANUAL')) {
            return $this->redirect($this->generateUrl('helpdesk_member_dashboard'));
        }

        $repository = $this->getDoctrine()->getRepository(Entity\PreparedResponses::class);
        $jsonData = $repository->getPreparesResponses($request->query, $container);

        return new JsonResponse($jsonData);
    }

    public function prepareResponseDeleteXhr(Request $request): Response
    {
        if (!$this->userService->isAccessAuthorized('ROLE_AGENT_MANAGE_WORKFLOW_MANUAL')) {
            return $this->redirect($this->generateUrl('helpdesk_member_dashboard'));
        }

        $json = [];
        if ('DELETE' === $request->getMethod()) {
            $em = $this->getDoctrine()->getManager();
            $id = $request->attributes->get('id');
            $preparedResponses = $em->getRepository(Entity\PreparedResponses::class)->find($id);

            $em->remove($preparedResponses);
            $em->flush();

            $json['alertClass'] = 'success';
            $json['alertMessage'] = $this->translator->trans('Success ! Prepared response removed successfully.');
        }

        return new JsonResponse($json);
    }

    public function getPreparedResponseActionOptionsXHR($entity, Request $request, ContainerInterface $container): Response
    {
        foreach ($this->preparedResponseListener->getRegisteredPreparedResponseActions() as $preparedResponseAction) {
            if ($preparedResponseAction->getId() == $entity) {
                $options = $preparedResponseAction->getOptions($container);

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
