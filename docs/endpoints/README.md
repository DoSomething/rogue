### v3

These endpoints use OAuth 2 to authenticate. [More information here](https://github.com/DoSomething/northstar/blob/master/documentation/authentication.md)

#### Signups

| Endpoint                            | Functionality                                         |
| ----------------------------------- | ----------------------------------------------------- |
| `POST /api/v3/signups`              | [Create a signup](signups.md#create-a-signup)         |
| `GET /api/v3/signups`               | [Get signups](signups.md#retrieve-all-signups)        |
| `GET /api/v3/signups/:signup_id`    | [Get a signup](signups.md#retrieve-a-specific-signup) |
| `PATCH /api/v3/signups/:signup_id`  | [Update a signup](signups.md#update-a-signup)         |
| `DELETE /api/v3/signups/:signup_id` | [Delete a signup](signups.md#delete-a-signup)         |

#### Posts

| Endpoint                        | Functionality                                   |
| ------------------------------- | ----------------------------------------------- |
| `POST /api/v3/posts`            | [Create a post](posts.md#create-a-post)         |
| `GET /api/v3/posts`             | [Get posts](posts.md#retrieve-all-posts)        |
| `GET /api/v3/posts/:post_id`    | [Get a post](posts.md#retrieve-a-specific-post) |
| `DELETE /api/v3/posts/:post_id` | [Delete a post](posts.md#delete-a-post)         |
| `PATCH /api/v3/posts/:post_id`  | [Update a post](posts.md#update-a-post)         |

#### Reactions

| Endpoint                                | Functionality                                                                |
| --------------------------------------- | ---------------------------------------------------------------------------- |
| `POST /api/v3/posts/:post_id/reactions` | [Create or update a Reaction](reactions.md#create-or-update-a-reaction)      |
| `GET /api/v3/posts/:post_id/reactions`  | [Get all reactions of a post](reactions.md#Retrieve-all-reactions-of-a-post) |

#### Reviews

| Endpoint                              | Functionality                                                       |
| ------------------------------------- | ------------------------------------------------------------------- |
| `POST /api/v3/posts/:post_id/reviews` | [Create or update a Review](reviews.md#create-or-update-a-reaction) |

#### Tags

| Endpoint                           | Functionality                                      |
| ---------------------------------- | -------------------------------------------------- |
| `POST /api/v3/posts/:post_id/tags` | [Tag or Untag a Post](tags.md#tag-or-untag-a-post) |

#### Events

| Endpoint             | Functionality                      |
| -------------------- | ---------------------------------- |
| `GET /api/v3/events` | [Get all events](events.md#events) |

#### Campaigns

| Endpoint                             | Functionality                                               |
| ------------------------------------ | ----------------------------------------------------------- |
| `GET /api/v3/campaigns`              | [Get campaigns](campaigns.md#retrieve-all-campaigns)        |
| `GET /api/v3/campaigns/:campaign_id` | [Get a campaign](campaigns.md#retrieve-a-specific-campaign) |

#### Actions

| Endpoint                         | Functionality                                          |
| -------------------------------- | ------------------------------------------------------ |
| `GET /api/v3/actions`            | [Get actions](actions.md#retrieve-all-actions)         |
| `GET /api/v3/actions/:action_id` | [Get an action](actions.md#retrieve-a-specific-action) |

#### Action Stats

| Endpoint                   | Functionality                       |
| -------------------------- | ----------------------------------- |
| `GET /api/v3/action-stats` | [Get action stats](action-stats.md) |

|
