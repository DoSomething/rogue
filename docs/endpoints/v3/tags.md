## Tags
All `v3 /posts/{post}/tags` endpoints require the `activity` scope. `Create`/`update`/`delete` endpoints also require the `write` scope.

## Tag a Post

```
POST /api/v3/posts/:post_id/tags
```
  - **tag_name**: (int|string) required.
    The label you want to tag a post with. 

Example Response:

```
{
    "data": {
        "id": 2,
        "signup_id": 1,
        "northstar_id": "559442cca59dbfca578b4bed",
        "media": {
            "url": "http://rogue.test/images/2",
            "original_image_url": "http://rogue.test/storage/uploads/reportback-items/40780-cb97cbb1c23c4cd9f1df2122b53fa1ce-1516983916.jpeg?time=1517002426",
            "caption": "Placeat hic incidunt repellendus fugit dolores non cum asperiores."
        },
        "quantity": 48,
        "tags": [
            "good-photo"
        ],
        "reactions": {
            "reacted": false,
            "total": null
        },
        "status": "pending",
        "source": "phoenix-next",
        "remote_addr": "142.55.196.64",
        "created_at": "2018-01-26T16:25:16+00:00",
        "updated_at": "2018-01-26T21:17:39+00:00"
    }
}
```

##Delete a Tag From a Post
```
DELETE /api/v3/posts/:post_id/tags
```
  - **tag_name**: (int|string) required.
    The tag you want to delete from the post

Example Response:

```
{
    "data": {
        "id": 2,
        "signup_id": 1,
        "northstar_id": "561563f9a59dbfbe378b4567",
        "media": {
            "url": "http://rogue.test/images/2",
            "original_image_url": "http://rogue.test/storage/uploads/reportback-items/76154-6dba2dea32f35e93c6ec7c70e1f3ceab-1517245151.jpeg?time=1517262927",
            "caption": "Dolores vitae amet animi eos libero porro."
        },
        "quantity": 41,
        "tags": [],
        "reactions": {
            "reacted": false,
            "total": null
        },
        "status": "pending",
        "source": "phoenix-next",
        "remote_addr": "50.59.121.68",
        "created_at": "2018-01-29T16:59:11+00:00",
        "updated_at": "2018-01-29T21:55:27+00:00"
    }
}
```
