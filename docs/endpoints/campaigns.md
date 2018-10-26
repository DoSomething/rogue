## Campaigns

## Retrieve All Campaigns (created in Rogue)

```
GET /api/v3/campaigns
```

Example Response:

```
{
    "data": [
        {
            "id": 9000,
            "internal_title": "Test campaign",
            "start_date": "2018-10-10 00:00:00",
            "end_date": "2018-12-10 00:00:00",
            "created_at": {
                "date": "2018-10-24 19:02:06.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "update_at": null
        },
        {
            "campaign_id": 9001,
            "internal_title": "2nd test campaign",
            "start_date": "2018-10-10 00:00:00",
            "end_date": "2018-12-10 00:00:00",
            "created_at": {
                "date": "2018-10-25 14:27:51.000000",
                "timezone_type": 3,
                "timezone": "UTC"
            },
            "update_at": null
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

## Retrieve A Specific Campaign

```
GET /api/v3/campaigns/:campaign_id
```

Example Response:

```
{
    "data": {
        "id": 9000,
        "internal_title": "Test Campaign",
        "start_date": "2018-10-10 00:00:00",
        "end_date": "2018-12-10 00:00:00",
        "created_at": {
            "date": "2018-10-24 19:02:06.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "update_at": null
    }
}
```
