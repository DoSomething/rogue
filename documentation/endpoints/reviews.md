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
    "postable_id": 352,
    "post_event_id": 816,
    "submission_type": "user",
    "postable_type": "Rogue\\Models\\Photo",
    "content": {
      "media": {
        "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/18-1487193185.jpeg",
        "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_18-1487193185.jpeg"
      },
      "caption": "new1",
      "status": "pending",
      "remote_addr": "207.110.19.130",
      "post_source": "runscope",
      "created_at": "2017-02-15T21:13:05+0000",
      "updated_at": "2017-03-03T22:15:01+0000"
    }
  }
}
```
