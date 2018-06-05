## Events

Retrieve all event data.

```
GET /api/v2/events
```

### Optional Query Parameters

- **filter[signup_id]** _(integer)_

  - Filter results by the given `signup_id`

Example Response:

```
{
  "data": [
    {
      "event_id": 1117,
      "eventable_id": 18,
      "event_type": "Rogue\\Models\\Signup",
      "content": {
        "id": "18",
        "northstar_id": "574dace47f43c21f1e0d674c",
        "campaign_id": "1173",
        "campaign_run_id": "1771",
        "quantity": "1",
        "quantity_pending": "5371",
        "why_participated": "Ut quidem aut et ex est beatae facilis.",
        "source": "phoenix-web",
        "created_at": "2017-08-14 21",
        "updated_at": "2017-09-28 18"
      },
      "user": "5589c991a59dbfa93d8b45ae",
      "created_at": "2017-09-28T18:21:27+00:00",
      "updated_at": "2017-09-28T18:21:27+00:00"
    },
    {
      "event_id": 747,
      "eventable_id": 18,
      "event_type": "Rogue\\Models\\Signup",
      "content": {
        "id": "18",
        "northstar_id": "574dace47f43c21f1e0d674c",
        "campaign_id": "1173",
        "campaign_run_id": "1771",
        "quantity": "4",
        "quantity_pending": "5371",
        "why_participated": "Ut quidem aut et ex est beatae facilis.",
        "source": "phoenix-web",
        "created_at": "2017-08-14 21",
        "updated_at": "2017-08-24 15"
      },
      "user": "5589c991a59dbfa93d8b45ae",
      "created_at": "2017-08-24T15:08:14+00:00",
      "updated_at": "2017-08-24T15:08:14+00:00"
      },
    }
  ]
}
```
