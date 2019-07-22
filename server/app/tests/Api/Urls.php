<?php

namespace App\Tests\Api;

final class Urls
{
//    private const EXAMPLE_TOURNAMENT_ID = '061e5afc-1b1a-4253-9e62-39ef9d7d725e';
//    const EXAMPLE_GAME_ID = '9eb5cf7e-6d6a-4e0a-8f54-d57d0fd2aac9-1538697600_9171_24225';

    const PROFILE_URL = 'profile';
    const PROFILE_LOGIN_URL = 'profile/login';
    const PROFILE_RTOKEN_URL = 'profile/refresh_token';
    const PROFILE_CHANGE_PASSWORD_URL = 'profile/change_password';
    const PROFILE_CHANGE_EMAIL_URL = 'profile/change_email';
    const PROFILE_RESTORE_PASSWORD_URL = 'profile/reset_password';
    const PROFILE_RESTORE_PASSWORD_CONFIRMATION_URL = 'profile/reset_password_confirmation';
    const PROFILE_SETTINGS_URL = 'profile/settings';

//    const TOURNAMENTS_ACTIVE_URL = 'tournaments/active';
//    const TOURNAMENTS_ALL_URL = 'tournaments/all';
//    const TOURNAMENT_GAMES_URL_SUCCESS = 'tournaments/' . self::EXAMPLE_TOURNAMENT_ID . '/games';
//    const TOURNAMENT_GAMES_URL_NOT_FOUND = 'tournaments/fake-id/games';
//    const TOURNAMENT_SCORE_TABLE_URL_SUCCESS = 'tournaments/' . self::EXAMPLE_TOURNAMENT_ID . '/scores_table';
//    const TOURNAMENT_SCORE_TABLE_URL_NOT_FOUND = 'tournaments/fake-id/scores_table';
//
//    const GAMES_FOR_DATE_URL = 'games/calendar';
//    const GAMES_VIEW_URL_SUCCESS = 'games/' . self::EXAMPLE_GAME_ID;
//    const GAMES_VIEW_URL_NOT_FOUND = 'games/fake-id';
//    const GAMES_VIEW_SCORES_TABLE_URL_SUCCESS = 'games/' . self::EXAMPLE_GAME_ID . '/scores_table';
//    const GAMES_VIEW_SCORES_TABLE_URL_NOT_FOUND = 'games/fake-id/scores_table';
//
//    const MARK_GAME_AS_FAV_URL_SUCCESS = 'favorite_games/' . self::EXAMPLE_GAME_ID . '/as_favorite';
//    const MARK_GAME_AS_FAV_URL_NOT_FOUND = 'favorite_games/fake-id/as_favorite';
//    const MY_FAV_GAMES_URL = 'favorite_games/my';
//
//    const VIEW_ONE_ANNOUNCEMENT_URL_SUCCESS = 'announcements/' . AnnouncementsCommand::ANNOUNCEMENT_ID;
//    const VIEW_ONE_ANNOUNCEMENT_URL_NOT_FOUND = 'announcements/fake-id';
//    const VIEW_LIST_ANNOUNCEMENT_URL = 'announcements';

    const FRIEND_INVITES_VIEW_OUTGOING_LIST_URL = 'friends/outgoing_invites';
    const FRIEND_INVITES_VIEW_INCOMING_LIST_URL = 'friends/incoming_invites';
    const FRIEND_VIEW_LIST_URL = 'friends';
    const FRIEND_VIEW_ONE_URL = 'friends/_id';
    const FRIEND_INVITES_CREATE_URL = 'friends/invites';
    const FRIEND_INVITES_ACCEPT_URL = 'friends/invites/_id/accept';
    const FRIEND_INVITES_CANCEL_URL = 'friends/invites/_id/cancel';
//
//    const ADVISE_GAME_URL = 'advised_games';
//    const DELETE_ADVISED_GAME_URL = 'advised_games/gameid';
//    const ADVISED_GAMES_INCOMING_URL = 'advised_games/incoming';
//    const ADVISED_GAMES_OUTGOING_URL = 'advised_games/outgoing';
//
//    const CHAT_MESSAGES_URL = 'chat/user_messages';
}
