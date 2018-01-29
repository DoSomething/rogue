# Rogue API
This is the Rogue API, it is used to capture activity from members.
All `POST` and `PUT` endpoints (except v3 endpoints) require an api key (`X-DS-Rogue-API-Key`) in the header to be submitted with the request. 

## Endpoints

### Web
#### Reviews
Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`PUT /reviews`                                 | [Update a post's status when admin reviews the post](endpoints/reviews.md#reviews)

#### Tags
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /tags`                                 | [Add or delete a post's tag](endpoints/tags.md#tags)

#### Posts
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /posts`                           | [Create a post](endpoints/posts.md#create-a-post-and/or-create/Update-a-signup)
`GET /posts`                            | [Get posts](endpoints/posts.md#retrieve-all-posts)
`DELETE /posts`                         | [Delete a post](endpoints/posts.md#delete-a-post)

### v1
#### Reportbacks
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`GET /api/v1/reportbacks`                      | [Get reportbacks](endpoints/reportbacks.md#reportbacks)


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
`POST /api/v2/reactions`                       | [Create or update a reaction](endpoints/reactions.md#create-or-update-a-reaction-v2)

#### Signups
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v2/signups`                         | [Create a signup](endpoints/signups.md#create-a-signup)

### v3
Instead of requiring an api key (`X-DS-Rogue-API-Key`) in the header to be submitted with the request, v3 endpoints use OAuth 2 to authenticate. 

#### Signups
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v3/signups`                         | [Create a signup](endpoints/v3/signups.md#create-a-signup)
`GET /api/v3/signups`                          | [Get signups](endpoints/v3/signups.md#retrieve-all-signups)
`GET /api/v3/signups/:signup_id`               | [Get a signup](endpoints/v3/signups.md#retrieve-a-specific-signup)
`PATCH /api/v3/signups/:signup_id`             | [Update a signup](endpoints/v3/signups.md#update-a-signup)   
`DELETE /api/v3/signups/:signup_id`            | [Delete a signup](endpoints/v3/signups.md#delete-a-signup)

#### Posts
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v3/posts`                           | [Create a post](endpoints/v3/posts.md#create-a-post-and/or-create/Update-a-signup)
`GET /api/v3/posts`                            | [Get posts](endpoints/v3/posts.md#retrieve-all-posts)
`GET /api/v3/posts/:post_id`                   | [Get a post](endpoints/v3/posts.md#retrieve-a-specific-post)
`DELETE /api/v3/posts/:post_id`                | [Delete a post](endpoints/v3/posts.md#delete-a-post)
`PATCH /api/v3/posts/:post_id`                 | [Update a post](endpoints/v3/posts.md#update-a-post)

#### Reactions
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v3/post/:post_id/reactions`         | [Create or update a Reaction](endpoints/v3/reactions.md#create-or-update-a-reaction-v3)
`GET /api/v3/post/:post_id/reactions`          | [Get all reactions of a post](endpoints/v3/reactions.md#Retrieve-all-reactions-of-a-post)

#### Reviews
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v3/reviews`                         | [Create or update a Review](endpoints/v3/reviews.md#create-or-update-a-reaction-v3)

#### Tags
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v3/posts/:post_id/tag`              | [Tag a Post](endpoints/v3/tags.md#tag-a-post)
`DELETE /api/v3/posts/:post_id/tag`            | [Delete a Tag from a Post](endpoints/v3/tags.md#delete-a-tag-from-a-post)
