## Posts

Create a Post and/or Create/Update a Signup

```
POST /api/v2/posts
```

  - **northstar_id**: (string) required.
    The northstar id of the user creating the post.
  - **campaign_id**: (int) required.
    The drupal node id of the campaign that the user's post is associated with. 
  - **campaign_run_id**: (int) required.
    The drupal campaign run node id of the campaign that the user's post is associated with.
  - **quantity**: (int).
    The number of reportback nouns verbed. 
  - **why_participated**: (string).
    The reason why the user participated.
  - **caption**: (string).
    Corresponding caption for the post.
  - **source**: (string).
    Where the post was submitted from.
  - **remote_addr**: (string).
    IP address of where the post is submitted from. 
  - **file**: (string) required.
    File string to save of post image.
  - **crop_x**: (int).
    The crop x coordinates of the post image if the user cropped the image.
  - **crop_y**: (int).
    The crop y coordinates of the post image if the user cropped the image.
  - **crop_width** (int).
    The copy width coordinates of the post image if the user cropped the image.
  - **crop_height** (int).
    The copy height coordinates of the post image if the user cropped the image.
  - **crop_rotate** (int).
    The copy rotate coordinates of the post image if the user cropped the image.

Example Response:

```
{
  "data": {
    "signup_id": 31,
    "northstar_id": "1233",
    "campaign_id": 74,
    "campaign_run_id": 1790,
    "content": {
      "type": "post_photo",
      "media": {
        "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/31-1485896463.jpeg",
        "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_31-1485896464.jpeg"
      },
      "caption": "1234",
      "status": "pending",
      "created_at": "2017-01-31T21:01:04+0000",
      "updated_at": "2017-01-31T21:01:04+0000"
    },
    "event": {
      "data": {
        "event_id": "559",
        "event_type": "post_photo",
        "submission_type": "user",
        "created_at": "2017-01-31T21:01:03+0000",
        "updated_at": "2017-01-31T21:01:03+0000"
      }
    }
  }
}
```
