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
  - e.g. `/reportbacks?filter[campaign_id]=47`
- **exclude** _(integer)_
  - The post id(s) to exclude in response. 
  - e.g. `/reportbacks?filter[exclude]=2,3,4`
- **as_user** _(string)_
  - The logged in user to display if they have reacted to the post or not.
  - e.g. `/reportbacks?as_user=1234`

Example Response:

```
{
{
  "data": [
    {
      "id": 2,
      "status": "accepted",
      "caption": "post 2",
      "event_id": 4,
      "media": {
        "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/1-676c9c02029df581d3668ae869d937b8-1491250786.jpeg",
        "type": "image"
      },
      "tagged": [
        "Good Photo",
        "Good For Storytelling"
      ],
      "created_at": "2017-04-03T20:19:47+00:00",
      "reportback": {
        "id": 1,
        "created_at": "2017-04-03T20:17:04+00:00",
        "updated_at": "2017-04-03T20:17:04+00:00",
        "quantity": 300,
        "why_participated": "Because",
        "flagged": "false"
      },
      "kudos": {
        "total": 0,
        "data": {
          "current_user": {
            "kudos_id": 1,
            "reacted": false
          },
          "term": {
            "name": "heart",
            "id": 641,
            "total": 0
          }
        }
      },
      "user": "1234"
    },
    {
      "id": 3,
      "status": "accepted",
      "caption": "post 3",
      "event_id": 6,
      "media": {
        "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/1-676c9c02029df581d3668ae869d937b8-1491250872.jpeg",
        "type": "image"
      },
      "tagged": [],
      "created_at": "2017-04-03T20:21:13+00:00",
      "reportback": {
        "id": 1,
        "created_at": "2017-04-03T20:17:04+00:00",
        "updated_at": "2017-04-03T20:17:04+00:00",
        "quantity": 300,
        "why_participated": "Because",
        "flagged": "false"
      },
      "kudos": {
        "total": 1,
        "data": {
          "current_user": {
            "kudos_id": 1,
            "reacted": true
          },
          "term": {
            "name": "heart",
            "id": 641,
            "total": 1
          }
        }
      },
      "user": "1234"
    },
    {
      "id": 4,
      "status": "accepted",
      "caption": "post 4",
      "event_id": 8,
      "media": {
        "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/1-676c9c02029df581d3668ae869d937b8-1491250890.jpeg",
        "type": "image"
      },
      "tagged": [],
      "created_at": "2017-04-03T20:21:31+00:00",
      "reportback": {
        "id": 1,
        "created_at": "2017-04-03T20:17:04+00:00",
        "updated_at": "2017-04-03T20:17:04+00:00",
        "quantity": 300,
        "why_participated": "Because",
        "flagged": "false"
      },
      "kudos": {
        "total": 0,
        "data": {
          "current_user": {
            "kudos_id": 1,
            "reacted": false
          },
          "term": {
            "name": "heart",
            "id": 641,
            "total": 0
          }
        }
      },
      "user": "1234"
    }
  ],
  "meta": {
    "pagination": {
      "total": 24,
      "count": 3,
      "per_page": 3,
      "current_page": 1,
      "total_pages": 8,
      "links": {
        "next_uri": "http://rogue.dev:8000/api/v1/reportbacks?limit=3&page=2"
      }
    }
  }
}
```
