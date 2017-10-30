# Rogue API
This is the Rogue API, it is used to capture activity from members.
All `POST` and `PUT` endpoints require an api key (`X-DS-Rogue-API-Key`) in the header to be submitted with the request. 

## Endpoints
#### Reviews
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`PUT /reviews`                                 | [Update a post's status when admin reviews the post](endpoints/reviews.md#reviews)

#### Tags
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /tags`                                 | [Add or delete a post's tag](endpoints/tags.md#tags)

### v1
#### Reportbacks
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`GET /api/v1/reportbacks`                      | [Get reportbacks](endpoints/reportbacks.md#reportbacks)

#### Reportback Items
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`PUT /api/v1/items`                            | [Reportback Items](endpoints/reportbackitems.md#reportbackitems)


### v2
#### Activity
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`GET /api/v2/activity`                         | [Get all user activity](endpoints/activity.md#activity)

#### Events
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`GET /api/v2/events`                         | [Get all events](endpoints/events.md#events)

#### Posts
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/posts`                           | [Create a post](endpoints/posts.md#create-a-post-and/or-create/Update-a-signup)
`GET /api/v2/posts`                            | [Get posts](endpoints/posts.md#retrieve-all-posts)
`DELETE /api/v2/posts`                         | [Delete a post](endpoints/posts.md#delete-a-post)

#### Reactions
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/reactions`                       | [Create or update a reaction](endpoints/reactions.md#reactions)

#### Signups
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/signups`                         | [Create a signup](endpoints/signups.md#create-a-signup)

### v3
#### Signups
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`GET /api/v3/signups`                          | [Get signups](endpoints/signups.md#retrieve-all-signups)
`GET /api/v3/signups/:signup_id`               | [Get a signup](endpoints/signups.md#retrieve-a-specific-signup)
`DELETE /api/v3/signups/:signup_id`            | [Delete a signup](endpoints/signups.md#delete-a-signup)
`PATCH /api/v3/signups/:signup_id`             | [Update a signup](endpoints/signups.md#update-a-signup)   

#### Posts
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`GET /api/v3/posts`                            | [Get posts](endpoints/posts.md#retrieve-all-posts)
`GET /api/v3/posts/:post_id`                   | [Get a post](endpoints/signups.md#retrieve-a-specific-post)
