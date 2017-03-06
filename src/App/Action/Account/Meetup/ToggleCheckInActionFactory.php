<?php
declare(strict_types=1);

namespace App\Action\Account\Meetup;

use App\Service\Authentication\AuthenticationServiceInterface;
use App\Service\Meetup\FindMeetupByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class ToggleCheckInActionFactory
{
    public function __invoke(ContainerInterface $container) : ToggleCheckInAction
    {
        return new ToggleCheckInAction(
            $container->get(EntityManagerInterface::class),
            $container->get(FindMeetupByUuidInterface::class),
            $container->get(AuthenticationServiceInterface::class)
        );
    }
}
