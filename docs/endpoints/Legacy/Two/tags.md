## Tags

Add or delete a post's tags.

```
POST api/v2/tags
```

- **post_id**: (string) required.
  The id of the post being tagged or untagged.
- **tag_name**: (string) required.
  The tag that is being added or deleted from a post. Note that this is the tag name and not the tag slug.

Example Response:

```
{
    "data": {
        "id": 2959,
        "signup_id": 4636,
        "northstar_id": "58c844ec7f43c24e4f621702",
        "media": {
            "url": "https://s3.amazonaws.com/ds-rogue-qa/uploads/reportback-items/edited_2959.jpeg",
            "caption": "This one's for you, Bill!"
        },
        "tagged": [
            "good-submission"
        ],
        "reactions": [],
        "status": "pending",
        "source": null,
        "remote_addr": "0.0.0.0",
        "created_at": "2017-03-15T03:21:42+00:00",
        "updated_at": "2017-03-15T03:21:42+00:00"
    }
}
```
