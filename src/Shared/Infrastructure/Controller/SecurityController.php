<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller;

use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Users\Application\Command\RegisterCommand;
use App\Users\Application\Service\LoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/security', name: 'security.')]
final class SecurityController
{
    public function __construct(private CommandBusInterface $commandBus, private LoginService $loginService)
    {
    }

    #[Route('/login/', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);

        $token = $this->loginService->login(
            $parameters['email'],
            $parameters['password'],
        );
        return new JsonResponse([
            'token' => $token
        ]);
    }

    #[Route('/register/', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);

        $this->commandBus->dispatch(RegisterCommand::createFromRequest($parameters));
        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}
