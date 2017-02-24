# Rogue API
This is the Rogue API, it is used to capture activity from members.
All `POST` and `PUT` endpoints require an api key (`X-DS-Rogue-API-Key`) in the header to be submitted with the request. 

## Endpoints

### v1
#### Reportbacks
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v1/reportbacks`                     | [Create a reportback](endpoints/reportbacks.md#reportbacks)

#### Reportback Items
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`PUT /api/v1/items`                            | [Reportback Items](endpoints/reportbackitems.md#reportbackitems)


### v2
#### Activity
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`GET /api/v2/activity`                         | [Get all user activity](endpoints/activity.md#activity)

#### Posts
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/posts`                           | [Create a post](endpoints/posts.md#posts)

#### Reactions
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/reactions`                       | [Create or update a reaction](endpoints/reactions.md#reactions)

#### Reviews
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/reviews`                         | [Update a post or multiple posts' status when admin reviews](endpoints/reviews.md#reviews)

#### Signups
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/signups`                         | [Create a signup](endpoints/signups.md#signups)
