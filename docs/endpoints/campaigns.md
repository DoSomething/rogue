## Campaigns

These endpoints require the role `admin` or `staff` to use.

## Retrieve All Campaigns (created in Rogue)

```
GET /api/v3/campaigns
```

### Optional Query Parameters

- **filter[column]** _(string)_
  - Filter results by the given column: `id`
  - You can filter by more than one value for a column, e.g. `/campaigns?filter[id]=121,122`
- **filter[internal_title]** _(string)_
  - Filter results by the given column: `internal_title`
  - You can filter by more than one value for a column, e.g. `/campaigns?filter[internal_title]=First Campaign,Second Campaign`
- **include** _(string)_
  - Include additional related records in the response: `actions`
  - e.g. `/posts?include=actions`

Example Response:

```
{
    "data": [
        {
            "id": 9000,
            "internal_title": "Test campaign",
            "start_date": "2001-03-13T00:00:00+00:00",
            "end_date": "2001-06-13T00:00:00+00:00",
            "created_at": "2018-12-05T16:24:05+00:00",
            "updated_at": "2018-12-05T16:24:05+00:00"
        },
        {
            "campaign_id": 9001,
            "internal_title": "2nd test campaign",
            "start_date": "2001-03-13T00:00:00+00:00",
            "end_date": "2001-06-13T00:00:00+00:00",
            "created_at": "2018-12-05T16:24:05+00:00",
            "updated_at": "2018-12-05T16:24:05+00:00"
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
        "start_date": "2001-03-13T00:00:00+00:00",
        "end_date": "2001-06-13T00:00:00+00:00",
        "created_at": "2018-12-05T16:24:05+00:00",
        "updated_at": "2018-12-05T16:24:05+00:00"
    }
}
```
