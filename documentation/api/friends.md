# Group Friends


## Get list of friends [GET /friends]

+ Parameters 
    + offset: 0 (number)
    + limit: 40 (number)

+ Request (application/json)
            
+ Response 200 (application/json)
    + Attributes
        + items (array[Friend])
        + paging (Paging Response)


## View friend [GET /friends/{friend_id}]

+ Parameters 
    + friend_id: `123e4567-e89b-12d3-a456-426655440000` (string)

+ Request (application/json)
            
+ Response 200 (application/json)
    + Attributes (Friend)


## Delete friend [DELETE /friends/{friend_id}]

+ Parameters 
    + friend_id: `123e4567-e89b-12d3-a456-426655440000` (string)

+ Request (application/json)
            
+ Response 204 (application/json)

        
## Get list of outgoing invites [GET /friends/outgoing_invites]

+ Parameters 
    + offset: 0 (number)
    + limit: 40 (number)

+ Request (application/json)
            
+ Response 200 (application/json)
    + Attributes
        + items (array[Invitation])
        + paging (Paging Response)
        
        
## Get list of incoming invites [GET /friends/incoming_invites]

+ Parameters 
    + offset: 0 (number)
    + limit: 40 (number)

+ Request (application/json)
            
+ Response 200 (application/json)
    + Attributes
        + items (array[Invitation])
        + paging (Paging Response)
        

## Send Mail Invite [PUT /friends/send_email_invite]

+ Request (application/json)
    + Attributes
        + email: `alex@gmail.com` (string)
            
+ Response 204 (application/json)
        
        
## Create invite [POST /friends/invites]

+ Parameters 
    + offset: 0 (number)
    + limit: 40 (number)

+ Request (application/json)
    + Attributes
        + nickname: `alex` (string)
            
+ Response 204 (application/json)
        
        
## Accept invite [PUT /friends/invites/{invite_id}/accept]

+ Parameters 
    + invite_id: `123e4567-e89b-12d3-a456-426655440000` (string)
            
+ Response 204 (application/json)
        
        
## Decline Invite [PUT /friends/invites/{invite_id}/decline]

+ Parameters 
    + invite_id: `123e4567-e89b-12d3-a456-426655440000` (string)
            
+ Response 204 (application/json)
        
        
## Cancel my invite [PUT /friends/invites/{invite_id}/cancel]

+ Parameters 
    + invite_id: `123e4567-e89b-12d3-a456-426655440000` (string)
            
+ Response 204 (application/json)




# Data Structures

## Friend (object)
+ include User short
+ countOfMessages: 5 (number)
+ countOfNewMessages: 5 (number)

## Invitation (object)
+ id: `123e4567-e89b-12d3-a456-426655440000` (string)
+ createdAt: `2010-12-31 00:00:00`  (string)
+ user (User short)
