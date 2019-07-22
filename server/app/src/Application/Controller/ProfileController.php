<?php

namespace App\Application\Controller;

use App\Application\Handler\Executor;
use App\Application\Handler\User\ChangeUserEmailHandler;
use App\Application\Handler\User\ChangeUserPasswordHandler;
use App\Application\Handler\User\RegisterUserHandler;
use App\Application\Handler\User\ResetUserPasswordHandler;
use App\Application\Request\User\ChangeUserEmailRequest;
use App\Application\Request\User\ChangeUserPasswordRequest;
use App\Application\Request\User\RegisterUserRequest;
use App\Application\Request\User\RestoreUserPasswordRequest;
use App\Application\Request\User\UpdateUserSettingsRequest;
use App\Domain\Common\ToArrayTransformer;
use App\Domain\User\Criteria\UserByEmailCriteria;
use App\Domain\User\Criteria\UserByNicknameCriteria;
use App\Domain\User\Transformer\UserFullTransformer;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route(methods={"POST"})
     */
    public function registerUserAction(
        Request $httpRequest,
        RegisterUserRequest $request,
        RegisterUserHandler $handler,
        AuthenticationSuccessHandler $authenticationSuccessHandler,
        Executor $executor
    ) : JsonResponse {
        /** @var User $user */
        $user = $executor->executeHandler(
            $handler,
            [
                $request->populateFromRequest($httpRequest),
            ]
        );

        $response = $authenticationSuccessHandler->handleAuthenticationSuccess($user);
        $response->setStatusCode(201);

        return $response;
    }

    /**
     * @Route(methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function getProfileAction(
        Executor $executor,
        UserFullTransformer $transformer
    ): array {
        return $executor->executeCallback(
            function () {
                return $this->getUser();
            },
            $transformer
        );
    }

    /**
     * @Route("/settings", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function getSettingsAction(
        Executor $executor,
        ToArrayTransformer $transformer
    ): array {
        return $executor->executeCallback(
            function () {
                return $this->getUser()->getSettings();
            },
            $transformer
        );
    }

    /**
     * @Route("/settings", methods={"PUT"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function setSettingsAction(
        Request $httpRequest,
        ToArrayTransformer $transformer,
        UpdateUserSettingsRequest $request,
        Executor $executor
    ): array {
        $request->populateFromRequest($httpRequest);

        return $executor->executeCallback(
            function () use ($request) {
                $user = $this->getUser();
                $user->updateSettings($request->getInputParams());

                return $user->getSettings();
            },
            $transformer
        );
    }

    /**
     * @Route("/change_password", methods={"PUT"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function changePasswordAction(
        Request $httpRequest,
        ChangeUserPasswordRequest $request,
        ChangeUserPasswordHandler $handler,
        Executor $executor
    ): void {
        $executor->executeHandler(
            $handler,
            [
                $this->getUser(),
                $request->populateFromRequest($httpRequest),
            ]
        );
    }

    /**
     * @Route("/reset_password", methods={"PUT"})
     */
    public function resetPasswordAction(
        Request $httpRequest,
        RestoreUserPasswordRequest $request,
        Executor $executor,
        DoctrineUserRepository $userRepository
    ): void {
        $request->populateFromRequest($httpRequest);

        $executor->executeCallback(
            function () use ($request, $userRepository) {
                if (($user = $userRepository->getOneByCriteria(
                        new UserByNicknameCriteria($request->getIdentity())
                    ))
                    || ($user = $userRepository->getOneByCriteria(
                        new UserByEmailCriteria($request->getIdentity())
                    ))
                ) {
                    /** @var User $user */
                    $user->createPasswordRestoreLink();
                }
            }
        );
    }

    /**
     * @Route(
     *     "/reset_password_confirmation",
     *      methods={"GET"},
     *      name="profile.reset_password_confirmation"
     * )
     */
    public function resetPasswordConfirmationAction(
        Request $request,
        Executor $executor,
        ResetUserPasswordHandler $handler,
        TranslatorInterface $translator,
        DoctrineUserRepository $userRepository
    ): Response {
        $result = false;

        /** @var User $user */
        if ($user = $userRepository->get((string) $request->get('id'))) {
            $translator->setLocale(
                $user->getSettings()->getLanguage()
            );

            $result = $executor->executeHandler(
                $handler,
                [
                    $user,
                    (string) $request->get('hash')
                ]
            );
        }

        return new Response(
            $this->renderView(
                'profile/reset_password_confirmation.html.twig',
                compact('result')
            ),
            $result ? 200 : 422
        );
    }

    /**
     * @Route("/change_email", methods={"PUT"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function changeEmailAction(
        Request $httpRequest,
        ChangeUserEmailRequest $request,
        Executor $executor
    ): void {
        $request->populateFromRequest($httpRequest);

        $executor->executeCallback(
            function () use ($request) {
                $this->getUser()->setEmail($request->getEmail());
            }
        );
    }
}