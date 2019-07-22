# Group Profile


## Register [POST /profile]

Referer can be empty

+ Request (application/json)
    + Attributes (object)
        + nickname: `alex` (string)
        + email: `alex@gmail.com` (string)
        + password: `password` (string)
        + referer: `vasya` (string)
        + language: `en|ru` (string)
        + timezone: `-12|-1|0|+1|+12` (string)
            
+ Response 201 (application/json)
    + Attributes (Login Response)


## Login [POST /profile/login]

+ Request (application/json)
    + Attributes (object)
        + nickname: `alex` (string)
        + password: `password` (string)
            
+ Response 200 (application/json)
    + Attributes (Login Response)


## Get new token by refresh_token [POST /profile/refresh_token]

+ Request (application/json)
    + Attributes (object)
        + refresh_token: `asdadase4` (string)
            
+ Response 200 (application/json)
    + Attributes (Login Response)


## Get my profile [GET /profile]

+ Request (application/json)
            
+ Response 200 (application/json)
    + Attributes (User full)


## Reset password [PUT /profile/reset_password]

+ Request (application/json)
    + Attributes (object)
        + identity: `alex@gmail.com|nickname` (string)
            
+ Response 204 (application/json)


## Reset password confirmation [GET /profile/reset_password_confirmation]

+ Parameters 
    + hash: `lala-alalaladsd-sdada` (string)
    + id: `lala-alalaladsd-sdada` (string)
            
+ Response 200

+ Response 400


## Change password [PUT /profile/change_password]

+ Request (application/json)
    + Attributes (object)
        + currentPassword: `pass` (string)
        + newPassword: `pass` (string)
            
+ Response 204 (application/json)


## Change email [PUT /profile/change_email]

+ Request (application/json)
    + Attributes (object)
        + currentPassword: `pass` (string)
        + email: `email@email.com` (string)
            
+ Response 204 (application/json)


## Settings [/profile/settings]


## Get settings [GET]

+ Request (application/json)
            
+ Response 200 (application/json)
    + Attributes (Settings)


## Set settings [PUT]

Can be changed separately

+ Request (application/json)
    + Attributes (object)
        + language: `en|ru` (string)
        + timezone: `-12|-1|0|+1|+12` (string)
            
+ Response 200 (application/json)
    + Attributes (Settings)



# Data Structures

## Login Response (object)
+ token: `sdfdsfdsfsdfs` (string)
+ user (User full)
+ refresh_token: `sdfdsfdsfsdfs` (string)

## Settings (object)
+ language: `en|ru` (string)
+ timezone: `+0|-1|+1` (string)