<?php
declare(strict_types=1);

namespace App\Action;

use App\Service\MeetupsServiceInterface;
use Interop\Container\ContainerInterface;

/**
 * @codeCoverageIgnore
 */
final class MeetupsIcsActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new MeetupsIcsAction(
            $container->get(MeetupsServiceInterface::class)
        );
    }
}
