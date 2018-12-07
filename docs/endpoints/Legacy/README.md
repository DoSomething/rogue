# Rogue API

This is the Rogue API, it is used to capture activity from members.

Most recent stable release is V3

### Documentation

[API v3](/docs/endpoints/v3/README.md)

## Endpoints

### v1

#### Reportbacks

| Endpoint                  | Functionality                                                      |
| ------------------------- | ------------------------------------------------------------------ |
| `GET /api/v1/reportbacks` | [Get reportbacks](endpoints/legacy/one/reportbacks.md#reportbacks) |

### v2

#### Activity

| Endpoint               | Functionality                                                      |
| ---------------------- | ------------------------------------------------------------------ |
| `GET /api/v2/activity` | [Get all user activity](endpoints/legacy/two/activity.md#activity) |

#### Posts

| Endpoint               | Functionality                                                                              |
| ---------------------- | ------------------------------------------------------------------------------------------ |
| `POST /api/v2/posts`   | [Create a post](endpoints/legacy/two/posts.md#create-a-post-and/or-create/Update-a-signup) |
| `GET /api/v2/posts`    | [Get posts](endpoints/legacy/two/posts.md#retrieve-all-posts)                              |
| `DELETE /api/v2/posts` | [Delete a post](endpoints/legacy/two/posts.md#delete-a-post)                               |

#### Reactions

| Endpoint                 | Functionality                                                                                   |
| ------------------------ | ----------------------------------------------------------------------------------------------- |
| `POST /api/v2/reactions` | [Create or update a reaction](endpoints/legacy/two/reactions.md#create-or-update-a-reaction-v2) |

#### Signups

| Endpoint               | Functionality                                                      |
| ---------------------- | ------------------------------------------------------------------ |
| `POST /api/v2/signups` | [Create a signup](endpoints/legacy/two/signups.md#create-a-signup) |
