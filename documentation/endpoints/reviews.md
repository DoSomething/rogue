## Reviews

Update a post or multiple posts' status when an admin reviews.

```
PUT /api/v2/reviews
```

  - **rogue_event_id**: (string) required.
    The reportback item's Rogue event id (id column in Rogue's event table).
  - **status**: (string) required.
    The status of the post. 
  - **reviewer**: (string) required.
    The reviewer's northstar id. 

Example Response:

```
{
  "data": [
    {
      "event_id": 407,
      "signup_id": 32,
      "northstar_id": "1234",
      "campaign_id": 1222,
      "campaign_run_id": 1822,
      "post": {
        "type": "photo",
        "media": {
          "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/32-1485545279.jpeg",
          "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_32-1485545279.jpeg"
        },
        "caption": "third post",
        "status": "approved",
        "created_at": "2017-01-27T19:28:00+0000",
        "updated_at": "2017-01-30T16:12:52+0000"
      },
      "event": {
        "data": {
          "event_id": "407",
          "event_type": "post_photo",
          "submission_type": "user",
          "created_at": "2017-01-27T19:27:59+0000",
          "updated_at": "2017-01-27T19:27:59+0000"
        }
      }
    },
    {
      "event_id": 410,
      "signup_id": 32,
      "northstar_id": "1234",
      "campaign_id": 1222,
      "campaign_run_id": 1822,
      "post": {
        "type": "photo",
        "media": {
          "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/32-1485545448.jpeg",
          "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_32-1485545449.jpeg"
        },
        "caption": "second post 1/27",
        "status": "approved",
        "created_at": "2017-01-27T19:30:49+0000",
        "updated_at": "2017-01-30T16:14:07+0000"
      },
      "event": {
        "data": {
          "event_id": "410",
          "event_type": "post_photo",
          "submission_type": "user",
          "created_at": "2017-01-27T19:30:48+0000",
          "updated_at": "2017-01-27T19:30:48+0000"
        }
      }
    }
  ]
}
```
