<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Domain\Exception\AuthenticationException;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Shared\Domain\Security\AuthenticatorServiceInterface;
use App\Shared\Domain\ValueObject\AuthUser;
use App\Shared\Infrastructure\Security\ValueObject\SymfonySecurityUser;
use ErrorException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LexikJwtAuthenticatorService implements AuthenticatorServiceInterface, EventSubscriberInterface
{
    private AuthUser $authUser;
    private string $pathRegexp = '';

    public function __construct(
        private readonly JWTTokenManagerInterface $tokenManager,
        private readonly TokenExtractorInterface $tokenExtractor,
        private $path
    ) {
        $this->pathRegexp = '/' . str_replace('/', '\/', $this->path) . '/';
        try {
            preg_match($this->pathRegexp,'');
        } catch (ErrorException $e) {
            throw new InvalidArgumentException(sprintf('Invalid path regexp %s', $this->path), 0, $e);
        }
        $this->authUser = new AuthUser('');
    }

    public function getAuthUser(): AuthUser
    {
        return $this->authUser;
    }

    public function getToken(string $id): string
    {
        return $this->tokenManager->create(new SymfonySecurityUser($id));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $uri = $event->getRequest()->getRequestUri();

        try {
            $token = $this->tokenExtractor->extract($event->getRequest());
            $token = $token === false ? '' : $token;

            try {
                if (!$payload = $this->tokenManager->parse($token)) {
                    throw new AuthenticationException('Invalid JWT Token');
                }
            } catch (JWTDecodeFailureException $e) {
                if (JWTDecodeFailureException::EXPIRED_TOKEN === $e->getReason()) {
                    throw new AuthenticationException('Expired token');
                }

                throw new AuthenticationException('Invalid JWT Token', 0, $e);
            }

            $idClaim = $this->tokenManager->getUserIdClaim();
            if (!isset($payload[$idClaim])) {
                throw new AuthenticationException(sprintf('Invalid payload %s', $idClaim));
            }

            $this->authUser = new AuthUser($payload[$idClaim]);
        } catch (AuthenticationException $e) {
            if (preg_match($this->pathRegexp, $uri) > 0) {
                throw $e;
            }
        }
    }
}