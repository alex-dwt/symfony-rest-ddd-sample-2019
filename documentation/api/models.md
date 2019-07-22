# Data Structures

## Paging Request (object)
+ offset: 0 (number)
+ limit: 40 (number)

## Paging Response (object)
+ offset: 0 (number)
+ count: 100 (number)
+ limit: 40 (number)




## User short (object)
+ id: `123e4567-e89b-12d3-a456-426655440000` (string)
+ nickname: `nickname` (string)
+ avatarUrl: `https://localhost/1.jpg` (string)

## User full (object)
+ include User short
+ email: `email@email.com` (string)