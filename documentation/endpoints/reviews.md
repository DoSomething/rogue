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
  - **event_type**: (string) required.
    The event type of the review. 

Example Response:

```
{
  "data": {
    "event_id": 816,
    "signup_id": 66,
    "northstar_id": "1234",
    "campaign_id": 47,
    "campaign_run_id": 1771,
    "post": {
      "type": "photo",
      "media": {
        "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/18-1487193185.jpeg",
        "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_18-1487193185.jpeg"
      },
      "caption": "new1",
      "status": "pending",
      "created_at": "2017-02-15T21:13:05+0000",
      "updated_at": "2017-03-03T22:15:01+0000"
    },
    "event": {
      "data": {
        "event_id": 816,
        "event_type": "post_photo",
        "submission_type": "user",
        "quantity": null,
        "quantity_pending": null,
        "why_participated": null,
        "caption": "new caption",
        "status": "pending",
        "source": "phoenix_web",
        "remote_addr": "10.0.2.2",
        "reason": null,
        "created_at": "2017-02-21T15:39:26+0000",
        "updated_at": "2017-02-21T15:39:26+0000"
      }
    }
  }
}
```
