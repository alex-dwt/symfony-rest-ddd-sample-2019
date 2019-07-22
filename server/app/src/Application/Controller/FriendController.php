<?php

namespace App\Application\Controller;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Handler\Executor;
use App\Application\Query\Friend\IncomingInvitesQuery;
use App\Application\Query\Friend\MyFriendsQuery;
use App\Application\Query\Friend\OutgoingInvitesQuery;
use App\Application\Query\Game\GamesForDateQuery;
use App\Application\Request\Friend\CreateFriendInviteRequest;
use App\Application\Request\Game\GameCalendarRequest;
use App\Application\Request\GeneralRequest;
use App\Application\Request\PaginationRequest;
use App\Domain\Friend\Transformer\FriendTransformer;
use App\Domain\Tournament\Game;
use App\Domain\Tournament\ScoreTable;
use App\Domain\Tournament\Transformer\GameFullTransformer;
use App\Domain\Tournament\Transformer\ScoreTableTransformer;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\DoctrineGameRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/friends")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class FriendController extends AbstractController
{
    /**
     * @Route("/outgoing_invites", methods={"GET"})
     */
    public function outgoingInvitesAction(
        Request $httpRequest,
        PaginationRequest $request,
        OutgoingInvitesQuery $query,
        Executor $executor
    ): array {
        $request->populateFromRequest($httpRequest);

        return $executor->executeCallback(
            function () use ($query, $request) {
                return $query->execute($request, $this->getUser());
            }
        );
    }

    /**
     * @Route("/incoming_invites", methods={"GET"})
     */
    public function incomingInvitesAction(
        Request $httpRequest,
        PaginationRequest $request,
        IncomingInvitesQuery $query,
        Executor $executor
    ): array {
        $request->populateFromRequest($httpRequest);

        return $executor->executeCallback(
            function () use ($query, $request) {
                return $query->execute($request, $this->getUser());
            }
        );
    }

    /**
     * @Route(methods={"GET"})
     */
    public function viewListAction(
        Request $httpRequest,
        PaginationRequest $request,
        MyFriendsQuery $query,
        Executor $executor
    ): array {
        $request->populateFromRequest($httpRequest);

        return $executor->executeCallback(
            function () use ($query, $request) {
                return $query->execute($request, $this->getUser());
            }
        );
    }

    /**
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     requirements={"id"="[a-z\d_-]+"}
     * )
     */
    public function viewOneFriendAction(
        string $id,
        Executor $executor
    ): array {
        return $executor->executeCallback(
            function () use ($id): User {
                if (!$friend = $this->getUser()->getFriend($id)) {
                    throw new EntityNotFoundException();
                }

                return $friend;
            },
            new FriendTransformer()
        );
    }

    /**
     * @Route(
     *     "/{id}",
     *     methods={"DELETE"},
     *     requirements={"id"="[a-z\d_-]+"}
     * )
     */
    public function deleteFriendAction(
        string $id,
        Executor $executor
    ): void {
        $executor->executeCallback(
            function () use ($id): void {
                if ($friend = $this->getUser()->getFriend($id)) {
                    $this->getUser()->removeFriend($friend);
                }
            }
        );
    }

    /**
     * @Route("/invites", methods={"POST"})
     */
    public function createInviteAction(
        Request $httpRequest,
        CreateFriendInviteRequest $request,
        Executor $executor
    ): void {
        $request->populateFromRequest($httpRequest);

        $executor->executeCallback(
            function () use ($request) {
                $this->getUser()->inviteFriend($request->getUser());
            }
        );
    }

    /**
     * @Route(
     *     "/invites/{id}/accept",
     *      methods={"PUT"},
     *      requirements={"id"="[a-z\d_-]+"}
     * )
     */
    public function acceptInviteAction(
        Executor $executor,
        string $id
    ): void {
        $executor->executeCallback(
            function () use ($id) {
                $this->getUser()->acceptFriendInvite($id);
            }
        );
    }

    /**
     * @Route(
     *     "/invites/{id}/cancel",
     *      methods={"PUT"},
     *      requirements={"id"="[a-z\d_-]+"}
     * )
     */
    public function cancelMyInviteAction(
        Executor $executor,
        string $id
    ): void {
        $executor->executeCallback(
            function () use ($id) {
                $this->getUser()->cancelMyFriendInvite($id);
            }
        );
    }
}