---
title: API Overview
sidebar_label: Overview
---

These endpoints use OAuth 2 to authenticate. [More information here](https://github.com/DoSomething/northstar/blob/master/documentation/authentication.md)

## Signups

| Endpoint                            | Functionality   |
| ----------------------------------- | --------------- |
| `POST /api/v3/signups`              | Create a signup |
| `GET /api/v3/signups`               | Get signups     |
| `GET /api/v3/signups/:signup_id`    | Get a signup    |
| `PATCH /api/v3/signups/:signup_id`  | Update a signup |
| `DELETE /api/v3/signups/:signup_id` | Delete a signup |

## Posts

| Endpoint                        | Functionality |
| ------------------------------- | ------------- |
| `POST /api/v3/posts`            | Create a post |
| `GET /api/v3/posts`             | Get posts     |
| `GET /api/v3/posts/:post_id`    | Get a post    |
| `DELETE /api/v3/posts/:post_id` | Delete a post |
| `PATCH /api/v3/posts/:post_id`  | Update a post |

## Reactions

| Endpoint                                | Functionality               |
| --------------------------------------- | --------------------------- |
| `POST /api/v3/posts/:post_id/reactions` | Create or update a Reaction |
| `GET /api/v3/posts/:post_id/reactions`  | Get all reactions of a post |

## Reviews

| Endpoint                              | Functionality             |
| ------------------------------------- | ------------------------- |
| `POST /api/v3/posts/:post_id/reviews` | Create or update a Review |

## Tags

| Endpoint                           | Functionality       |
| ---------------------------------- | ------------------- |
| `POST /api/v3/posts/:post_id/tags` | Tag or Untag a Post |

## Events

| Endpoint             | Functionality  |
| -------------------- | -------------- |
| `GET /api/v3/events` | Get all events |

## Campaigns

| Endpoint                             | Functionality  |
| ------------------------------------ | -------------- |
| `GET /api/v3/campaigns`              | Get campaigns  |
| `GET /api/v3/campaigns/:campaign_id` | Get a campaign |

## Actions

| Endpoint                         | Functionality |
| -------------------------------- | ------------- |
| `GET /api/v3/actions`            | Get actions   |
| `GET /api/v3/actions/:action_id` | Get an action |

## Action Stats

| Endpoint                   | Functionality    |
| -------------------------- | ---------------- |
| `GET /api/v3/action-stats` | Get action stats |

## Groups

| Endpoint                       | Functionality |
| ------------------------------ | ------------- |
| `GET /api/v3/groups`           | Get groups    |
| `GET /api/v3/groups/:group_id` | Get a group   |

## Group Types

| Endpoint                                 | Functionality     |
| ---------------------------------------- | ----------------- |
| `GET /api/v3/group-types`                | Get group types]  |
| `GET /api/v3/group-types/:group_type_id` | Get a group type] |

## Users

| Endpoint                        | Functionality            |
| ------------------------------- | ------------------------ |
| `DELETE /api/v3/users/:user_id` | Delete a user's activity |
