## Actions

These endpoints require the role `admin` or `staff` to use.

## Retrieve All Actions (created in Rogue)

```
GET /api/v3/actions
```

### Optional Query Parameters

- **filter[column]** _(string)_
  - Filter results by the given column: `id`, `campaign_id`, `callpower_campaign_id`
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
            "post_label": "Voter Registration",
            "action_type": null,
            "action_label": null,
            "time_commitment": null,
            "time_commitment_label": null,
            "callpower_campaign_id": null,
            "reportback": 1,
            "civic_action": 1,
            "scholarship_entry": 0,
            "anonymous": 0,
            "online": 0,
            "quiz": 0,
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
            "post_label": "Voter Registration",
            "action_type": null,
            "action_label": null,
            "time_commitment": null,
            "time_commitment_label": null,
            "callpower_campaign_id": null,
            "reportback": 1,
            "civic_action": 1,
            "scholarship_entry": 0,
            "anonymous": 0,
            "online": 0,
            "quiz": 0,
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
  "post_label": "Social Share",
  "action_type": "share-something",
  "action_label": "Share Something",
  "time_commitment": "<0.5",
  "time_commitment_label": "< 30 minutes",
  "callpower_campaign_id": null,
  "reportback": 0,
  "civic_action": 0,
  "scholarship_entry": 0,
  "anonymous": 0,
  "online": 1,
  "quiz": 0,
  "noun": "resources",
  "verb": "shared",
  "created_at": "2019-01-23T21:23:42+00:00",
  "updated_at": "2019-01-23T21:23:42+00:00"
  }
}
```
