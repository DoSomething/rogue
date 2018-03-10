### v3
These endpoints use OAuth 2 to authenticate. [More information here](https://github.com/DoSomething/northstar/blob/master/documentation/authentication.md)

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
`POST /api/v3/posts/:post_id/tags`             | [Tag a Post](endpoints/v3/tags.md#tag-a-post)
`DELETE /api/v3/posts/:post_id/tags`           | [Delete a Tag from a Post](endpoints/v3/tags.md#delete-a-tag-from-a-post)