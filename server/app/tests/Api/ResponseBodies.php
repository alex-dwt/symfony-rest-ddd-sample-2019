<?php

namespace App\Tests\Api;

final class ResponseBodies
{
    const PAGINATED_RESPONSE = [
        'paging' => 'array',
        'items' => 'array',
    ];

    const PROFILE_VIEW_RESPONSE = [
        'id' => 'string',
        'nickname' => 'string',
        'avatarUrl' => 'string',
        'email' => 'string',
    ];
    const PROFILE_SHORT_VIEW_RESPONSE = [
        'id' => 'string',
        'nickname' => 'string',
        'avatarUrl' => 'string',
    ];
    const PROFILE_REGISTRATION_RESPONSE = [
        'token' => 'string',
        'user' => self::PROFILE_VIEW_RESPONSE,
        'refresh_token' => 'string',
    ];
    const PROFILE_SETTINGS_RESPONSE = [
        'language' => 'string',
        'timezone' => 'string',
    ];


    const TOURNAMENT_SCORE_TABLE_RESPONSE = [
        'id' => 'string',
        'tournament' => [
            'id' => 'string',
            'country' => 'string',
            'name' => 'string',
            'isArchived' => 'bool',
        ],
        'teams' => 'array',
    ];


    const GAME_RESPONSE = [
        'id' => 'string',
        'tournamentId' => 'string',
        'type' => 'string',
        'favoriteForUsers' => 'array',
        'datetime' => 'string',
        'extraInfo' => 'array',
        'homeScore' => 'int',
        'guestScore' => 'int',
        'homeTeamId' => 'int',
        'totalScore' => 'int',
        'guestTeamId' => 'int',
        'homeTeamName' => 'string',
        'guestTeamName' => 'string',
        'tournamentName' => 'string',
        'bookmakersStats' => 'array',
        'tournamentCountry' => 'string',
    ];


    const ANNOUNCEMENT_RESPONSE = [
        'id' => 'string',
        'title' => 'string',
        'text' => 'string',
        'createdAt' => 'string',
        'isViewed' => 'bool',
    ];


    const FRIEND_RESPONSE = [
        'id' => 'string',
        'nickname' => 'string',
        'avatarUrl' => 'string',
        'countOfMessages' => 'int',
        'countOfNewMessages' => 'int',
    ];


    const ONE_ADVISED_GAME_FULL = [
        'id' => 'string',
        'game' => 'array',
        'advisedUsers' => 'array',
    ];


    const CHAT_MESSAGE_RESPONSE = [
        'id' => 'string',
        'text' => 'string',
        'isViewed' => 'bool',
        'createdAt' => 'string',
        'sender' => self::PROFILE_SHORT_VIEW_RESPONSE,
        'recipient' => self::PROFILE_SHORT_VIEW_RESPONSE,
    ];
}
