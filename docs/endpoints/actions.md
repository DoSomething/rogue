## Actions

These endpoints require the role `admin` or `staff` to use.

## Retrieve All Actions (created in Rogue)

```
GET /api/v3/actions
```

### Optional Query Parameters

- **filter[column]** _(string)_
  - Filter results by the given column: `id`, `callpower_campaign_id`
  - You can filter by more than one value for a column, e.g. `/actions?filter[id]=121,122`

Example Response:

```
{
    "data": [
        {
            "id": 11,
            "name": "august-2018-turbovote",
            "campaign_id": 9005,
            "post_type": "voter-reg",
            "callpower_campaign_id": null,
            "reportback": 1,
            "civic_action": 1,
            "scholarship_entry": 0,
            "noun": "votes",
            "verb": "registered",
            "created_at": "2019-01-23T21:23:42+00:00",
            "updated_at": "2019-01-23T21:23:42+00:00"
        },
        {
            "id": 12,
            "name": "december-2018-turbovote",
            "campaign_id": 9005,
            "post_type": "voter-reg",
            "callpower_campaign_id": 5,
            "reportback": 1,
            "civic_action": 1,
            "scholarship_entry": 0,
            "noun": "votes",
            "verb": "registered",
            "created_at": "2019-01-23T21:23:42+00:00",
            "updated_at": "2019-01-23T21:23:42+00:00"
        }
    ],
    "meta": {
        "pagination": {
            "total": 2,
            "count": 2,
            "per_page": 20,
            "current_page": 1,
            "total_pages": 1,
            "links": []
        }
    }
}
```

## Retrieve A Specific Action

```
GET /api/v3/actions/:action_id
```

Example Response:

```
{
  "data": {
  "id": 8,
  "name": "action-page",
  "campaign_id": 9003,
  "post_type": "share-social",
  "callpower_campaign_id": 5,
  "reportback": 0,
  "civic_action": 0,
  "scholarship_entry": 0,
  "noun": "resources",
  "verb": "shared",
  "created_at": "2019-01-23T21:23:42+00:00",
  "updated_at": "2019-01-23T21:23:42+00:00"
  }
}
```
