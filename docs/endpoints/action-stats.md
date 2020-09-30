## Action Stats

Action stats are used to store aggregate calculations for an action and a school. They are created or updated per action and school when a post that contains a school ID is reviewed.

## Retrieve All Action Stats

```
GET /api/v3/action-stats
```

### Optional Query Parameters

- **filter[action_id]** _(integer)_

  - The action id to filter by.
  - e.g. `/action-stats?filter[action_id]=954`

- **filter[exclude]** _(integer|string)_

  - The id(s) to exclude in response. If `exclude_by_field` filter is not set, this defaults to filter by the action stat id.
  - e.g. `/action-stats?filter[exclude]=2,3,4`

- **filter[exclude_by_field]** _(string)_

  - The field to exclude by, if an `exclude` filter is also set.
  - e.g. `/action-stats?filter[exclude]=US-AL,US-HI&filter[exclude_by_field]=location`

- **filter[group_type_id]** _(int)_

  - The group type id to filter the response by, joining on [`groups`](../groups.md) by `school_id`.
  - e.g. `/action-stats?filter[location]=US-NY`

- **filter[location]** _(string)_

  - The location to filter the response by.
  - e.g. `/action-stats?filter[location]=US-NY`

- **filter[school_id]** _(string)_

  - The school id to filter the response by.
  - e.g. `/action-stats?filter[school_id]=4809500`

- **orderBy** _(string)_
  - Order results by column. Supported columns: `id`, `impact`
  - e.g. `/action-stats?orderBy=impact,desc`

Example Response:

```
{
  "data": [
    {
      "id": 1,
      "action_id": 1,
      "school_id": "3401457",
      "location": "US-SC",
      "impact": 37,
      "created_at": "2019-12-04T21:28:26+00:00",
      "updated_at": "2019-12-04T22:33:03+00:00"
    },
    {
      "id": 2,
      "action_id": 1,
      "school_id": "4802532",
      "location": "US-NJ",
      "impact": 43,
      "created_at": "2019-12-04T22:05:29+00:00",
      "updated_at": "2019-12-04T22:05:29+00:00"
    }
  ],
  "meta": {
    "pagination": {
      "total": 2,
      "count": 2,
      "per_page": 20,
      "current_page": 1,
      "total_pages": 1,
      "links": [

      ]
    }
  }
```
