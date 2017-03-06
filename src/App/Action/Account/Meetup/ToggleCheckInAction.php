<?php
declare(strict_types=1);

namespace App\Action\Account\Meetup;

use App\Service\Authentication\AuthenticationServiceInterface;
use App\Service\Meetup\FindMeetupByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

final class ToggleCheckInAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FindMeetupByUuidInterface
     */
    private $findMeetupByUuid;

    /**
     * @var AuthenticationServiceInterface
     */
    private $authenticationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FindMeetupByUuidInterface $findMeetupByUuid,
        AuthenticationServiceInterface $authenticationService
    ) {
        $this->entityManager = $entityManager;
        $this->findMeetupByUuid = $findMeetupByUuid;
        $this->authenticationService = $authenticationService;
    }

    public function __invoke(Request $request, Response $response, callable $next = null) : Response
    {
        $meetup = $this->findMeetupByUuid->__invoke(Uuid::fromString($request->getAttribute('uuid')));
        $user = $this->authenticationService->getIdentity();

        $now = new \DateTimeImmutable();

        return new JsonResponse(
            $this->entityManager->transactional(function () use ($user, $meetup, $now) {

                if ($user->isCheckedIn($meetup)) {
                    $meetup->cancelCheckIn($user);
                } else {
                    $meetup->checkIn($user);
                }

                return [
                    'checkedIn' => $user->isCheckedIn($meetup),
                ];
            }),
            200
        );
    }
}
