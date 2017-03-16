## Activity

Retrieve all user activity data. 

```
GET /api/v2/activity
```
### Optional Query Parameters
- **limit** _(default is 20)_
  - Set the number of records to return in a single response.
  - e.g. `/activity?limit=35`
- **page** _(integer)_
  - For pagination, specify page of activity to return in the response.
  - e.g. `/activity?page=2`
- **campaign_id** _(integer)_
  - The nid(s) to filter the response by.
  - e.g. `/activity?filter[campaign_id]=47,49`
- **campaign_run_id** _(integer)_
  - The campaign run nid(s) to filter the response by.
  - e.g. `/activity?filter[campaign_run_id]=1771`
- **user** _(integer)_
  - Whether or not to include information about the user with the response.
  - e.g. `/activity?include=user`


Example Response:

```
{
  "data": [
    {
      "signup_id": 15,
      "signup_event_id": 121,
      "submission_type": "user",
      "northstar_id": "1234",
      "campaign_id": 47,
      "campaign_run_id": 1771,
      "quantity": null,
      "quantity_pending": 3,
      "why_participated": "because i love to help!",
      "signup_source": "phoenix-web",
      "created_at": "2017-01-20T20:26:56+0000",
      "updated_at": "2017-01-20T20:26:56+0000",
      "posts": {
        "data": [
          {
            "postable_id": 72,
            "post_event_id": 122,
            "submission_type": "user",
            "postable_type": "Rogue\\Models\\Photo",
            "content": {
              "media": {
                "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/15-1484944016.jpeg",
                "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_15-1484944017.jpeg"
              },
              "caption": "caption",
              "status": "pending",
              "total_reactions": 3,
              "remote_addr": "10.0.2.2",
              "post_source": "phoenix-web",
              "created_at": "2017-01-20T20:26:57+0000",
              "updated_at": "2017-01-20T20:26:57+0000"
            }
          },
          {
            "postable_id": 73,
            "post_event_id": 123,
            "submission_type": "user",
            "postable_type": "Rogue\\Models\\Photo",
            "content": {
              "media": {
                "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/15-1484944137.jpeg",
                "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_15-1484944138.jpeg"
              },
              "caption": "lolz",
              "status": "pending",
              "total_reactions": 43,
              "remote_addr": "10.0.2.2",
              "post_source": "phoenix-web",
              "created_at": "2017-01-20T20:28:59+0000",
              "updated_at": "2017-01-20T20:28:59+0000"
            }
          },
          {
            "postable_id": 74,
            "post_event_id": 124,
            "submission_type": "user",
            "postable_type": "Rogue\\Models\\Photo",
            "content": {
              "media": {
                "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/15-1484944153.jpeg",
                "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_15-1484944153.jpeg"
              },
              "caption": "lolz",
              "status": "pending",
              "total_reactions": 2,
              "remote_addr": "10.0.2.2",
              "post_source": "phoenix-web",
              "created_at": "2017-01-20T20:29:14+0000",
              "updated_at": "2017-01-20T20:29:14+0000"
            }
          }
        ]
      },
      "events": {
        "data": [
          {
            "event_id": 27,
            "event_type": "post_photo",
            "submission_type": "user",
            "quantity": 3,
            "quantity_pending": 5,
            "why_participated": "to test",
            "caption": "testing caption\n",
            "status": "pending\n",
            "source": "chloe-testing",
            "remote_addr": null,
            "reason": null,
            "created_at": "2017-01-18T20:31:25+0000",
            "updated_at": "2017-01-18T20:31:25+0000"
          },
          {
            "event_id": 28,
            "event_type": "signup",
            "submission_type": "user",
            "quantity": null,
            "quantity_pending": null,
            "why_participated": null,
            "caption": null,
            "status": null,
            "source": null,
            "remote_addr": null,
            "reason": null,
            "created_at": "2017-01-18T20:33:39+0000",
            "updated_at": "2017-01-18T20:33:39+0000"
          }
        ]
      }
      "user": {
        "data": {
          "first_name": "Chloe"
        }
      }
    }
  ],
  "meta": {
    "pagination": {
      "total": 1,
      "count": 1,
      "per_page": 20,
      "current_page": 1,
      "total_pages": 1,
      "links": []
    }
  }
}
```
