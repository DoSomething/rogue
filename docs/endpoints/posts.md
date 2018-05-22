## Posts
All `v3 /posts` endpoints require the `activity` scope. `Create`/`update`/`delete` endpoints also require the `write` scope.

## Retrieve All Posts

```
GET /api/v3/posts
```

Posts are returned in reverse chronological order.

Only admins and post owners will have `tags`, `source`, `remote_addr` (which will be `000.000.00.00` for all posts in compliance with GDPR), and hidden posts (posts that are tagged 'Hide In Gallery') returned in the response.

Anonymous requests will only return accepted posts. Logged-in users can see accepted posts & any of their own pending or rejected posts. Staff can see anything!

### Optional Query Parameters
- **limit**
  - Set the number of records to return in a single response.
  - e.g. `/posts?limit=35`
- **page** _(integer)_
  - For pagination, specify page of activity to return in the response.
  - e.g. `/posts?page=2`
- **filter[campaign_id]** _(integer)_
  - The campaign ID to filter the response by.
  - e.g. `/posts?filter[campaign_id]=47`
- **filter[northstar_id]** _(integer)_
  - The northstar_id to filter the response by.
  - e.g. `/posts?filter[northstar_id]=47asdf23abc`
- **filter[status]** _(string)_
  - The string to filter the response by.
  - e.g. `/posts?filter[status]=accepted`
- **filter[exclude]** _(integer)_
  - The post id(s) to exclude in response.
  - e.g. `/posts?filter[exclude]=2,3,4`
- **filter[tag]** _(string)_
  - The tag(s) to filter the response by.
  - Tag is passed in as tag_slug.
  - e.g. `/posts?filter[tag]=good-submission,good-for-sponsor`
- **include** _(string)_
  - Include additional related records in the response: `signup`, `siblings`
  - e.g. `/posts?include=signup,siblings`

Example Response:

```
{
    "data": [
        {
            "id": 2984,
            "signup_id": 4673,
            "northstar_id": "5594429fa59dbfc9578b48f4",
            "media": {
                "url": "https://s3.amazonaws.com/ds-rogue-qa/uploads/reportback-items/edited_2984.jpeg",
                "text": null
            },
            "quantity": "12",
            "tags": [],
            "reactions": {
                "reacted": true,
                "total": 2
            },
            "status": "accepted",
            "source": null,
            "remote_addr": "000.000.00.00",
            "created_at": "2016-11-30T21:21:24+00:00",
            "updated_at": "2017-08-02T14:11:26+00:00"
        },
        {
            "id": 3655,
            "signup_id": 5787,
            "northstar_id": "5575e568a59dbf3b7a8b4572",
            "media": {
                "url": "https://s3.amazonaws.com/ds-rogue-qa/uploads/reportback-items/edited_3655.jpeg",
                "text": "Perhaps you CAN be of some assistance, Bill"
            },
            "quantity": "12",
            "tags": [],
            "reactions": {
                "reacted": false,
                "total": 8
            },
            "status": "accepted",
            "source": null,
            "remote_addr": "000.000.00.00",
            "created_at": "2016-02-10T16:19:25+00:00",
            "updated_at": "2017-08-02T14:11:35+00:00"
        }
    ],
    "meta": {
        "pagination": {
            "total": 53,
            "count": 2,
            "per_page": 2,
            "current_page": 1,
            "total_pages": 27,
            "links": {
                "next": "http://rogue.app/api/v2/posts?filter%5Bcampaign_id%5D=1631%2C12&filter%5Bstatus%5D=accepted&filter%5Bexclude%5D=2962%2C3654&limit=2&as_user=559442cca59dbfc&page=2"
            }
        }
    }
}
```
## Retrieve A Specific Post

```
GET /api/v3/posts/:post_id
```

Only admins and post owners will have `tags`, `source`, and `remote_addr` (which will be `000.000.00.00` for all posts in compliance with GDPR)returned in the response.

Anonymous requests will only return accepted posts. Logged-in users can see accepted posts & any of their own pending or rejected posts. Staff can see anything!

Example Response:

```
{
  "data": {
    "id": 332,
    "signup_id": 289,
    "northstar_id": "5589c991a59dbfa93d8b45ae",
    "media": {
      "url": "http://localhost/storage/uploads/reportback-items/edited_332.jpeg?time=1509129880",
      "original_image_url": "http://localhost/storage/uploads/reportback-items/289-923df5957838355206574f72d5520f0f-1509115822.jpeg?time=1509129880",
      "text": "fe"
    },
    "quantity": "12",
    "tags": [],
    "reactions": {
      "reacted": false,
      "total": null
    },
    "status": "accepted",
    "source": "rogue-admin",
    "remote_addr": "000.000.00.00",
    "created_at": "2017-10-27T14:50:22+00:00",
    "updated_at": "2017-10-27T14:50:22+00:00"
  }
}
```

## Create a Post

This will automatically create or update the corresponding signup.

```
POST /api/v3/posts
```
  - **campaign_id**: (int|string) required.
    The Drupal/Contentful ID of the campaign that the user's post is associated with.
  - **campaign_run_id**: (int)
    The drupal campaign run node id of the campaign that the user's post is associated with.
  - **type**: (string) required.
    The type of post submitted e.g. photo, voter-reg, text
  - **action**: (string) required.
    Describes the bucket the action is tied to. A campaign could ask for multiple types of actions throughout the life of the campaign.
  - **quantity**: (int|nullable) optional.
    The number of reportback nouns verbed. Can be `null`.
  - **why_participated**: (string).
    The reason why the user participated.
  - **text**: (string).
    Corresponding text for the post (could be photo caption or other words). 256 max characters.
  - **status**: (string).
    Option to set status upon creation if admin uploads post for user.
  - **file**: (multipart/form-data) required for photo posts.
    File to save of post image.
  - **details** (json).
    A JSON field to store extra details about a post.
  - **dont_send_to_blink** (boolean) optional.
    If included and true, the data for this Post will not be sent to Blink.

Example Response:

```
{
  "data": {
    "id": 340,
    "signup_id": 784,
    "type": photo,
    "action": default,
    "northstar_id": "5571df46a59db12346dsb456d",
    "media": {
      "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_214.jpeg",
      "original_image_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/128-482cab927f6529c7f5e5c4bfd2594186-1501090354.jpeg",
      "text": "Captioning captions",
    },
    "quantity": "12",
    "status": "pending",
    "remote_addr": "000.000.00.00",
    "post_source": "runscope",
    "created_at": "2017-02-15T18:14:58+0000",
    "updated_at": "2017-02-15T18:14:58+0000"
  }
}
```

## Delete a Post

```
DELETE /api/v3/posts/:post_id
```
Example Response:

```
{
    "code": 200,
    "message": "Post deleted."
}

```

## Update a Post

```
PATCH /api/v3/posts/:post_id
```

  - **text**: (string)
    The text of the post.
  - **quantity**: (int)
    The quantity of items in the post.

Example request body:
```
[
  "text" => "Here is a brand new caption"
  "quantity" => "7"
]
```

Example response:
```
{
  "data": {
      "id": 332,
      "signup_id": 289,
      "northstar_id": "5589c991a59dbfa93d8b45ae",
      "media": {
          "url": "http://localhost/storage/uploads/reportback-items/edited_332.jpeg?time=1509379493",
          "original_image_url": "http://localhost/storage/uploads/reportback-items/289-923df5957838355206574f72d5520f0f-1509115822.jpeg?time=1509379493",
          "text": "Here is a brand new caption"
      },
      "quantity": "7",
      "tags": [],
      "reactions": {
          "reacted": false,
          "total": null
      },
      "status": "accepted",
      "source": "rogue-admin",
      "remote_addr": "000.000.00.00",
      "created_at": "2017-10-27T14:50:22+00:00",
      "updated_at": "2017-10-30T16:04:53+00:00"
  }
}
```
