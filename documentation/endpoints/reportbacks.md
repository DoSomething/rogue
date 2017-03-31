## Reportbacks

Retrieve all Posts.

```
GET /api/v1/reportbacks
```

### Optional Query Parameters
- **limit**
  - Set the number of records to return in a single response.
  - e.g. `/reportbacks?limit=35`
- **page** _(integer)_
  - For pagination, specify page of activity to return in the response.
  - e.g. `/reportbacks?page=2`
- **campaign_id** _(integer)_
  - The nid to filter the response by.
  - e.g. `/activity?filter[campaign_id]=47`

Example Response:

```
{
  "data": [
    {
      "id": 4,
      "status": "approved",
      "caption": "Ipsam fugit magnam impedit sit quia quo.",
      "uri": "https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg",
      "media": {
        "uri": "https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg",
        "type": "image"
      },
      "created_at": "2017-03-28T14:33:32+00:00",
      "reportback": {
        "id": 2,
        "created_at": "2017-03-28T14:33:32+00:00",
        "updated_at": "2017-03-28T14:33:32+00:00",
        "quantity": null,
        "why_participated": "Vel excepturi soluta aut natus ut.",
        "flagged": "false"
      }
    },
    {
      "id": 6,
      "status": "approved",
      "caption": "Nostrum et nemo totam quia occaecati.",
      "uri": "https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg",
      "media": {
        "uri": "https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg",
        "type": "image"
      },
      "created_at": "2017-03-28T14:33:32+00:00",
      "reportback": {
        "id": 2,
        "created_at": "2017-03-28T14:33:32+00:00",
        "updated_at": "2017-03-28T14:33:32+00:00",
        "quantity": null,
        "why_participated": "Vel excepturi soluta aut natus ut.",
        "flagged": "false"
      }
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
