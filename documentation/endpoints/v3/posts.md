## Posts (v3)

## Retrieve All Posts

```
GET /api/v3/posts
```

Posts are returned in reverse chronological order. Anonymous requests will only return accepted posts. Logged-in users can see accepted posts & any of their own pending or rejected posts. Staff can see anything!

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
  - e.g. `/posts?filter[tag]=good-photo,good-for-sponsor`
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
                "caption": null
            },
            "tags": [],
            "reactions": {
                "reacted": true,
                "total": 2
            },
            "status": "accepted",
            "source": null,
            "remote_addr": "52.3.68.224, 52.3.68.224",
            "created_at": "2016-11-30T21:21:24+00:00",
            "updated_at": "2017-08-02T14:11:26+00:00"
        },
        {
            "id": 3655,
            "signup_id": 5787,
            "northstar_id": "5575e568a59dbf3b7a8b4572",
            "media": {
                "url": "https://s3.amazonaws.com/ds-rogue-qa/uploads/reportback-items/edited_3655.jpeg",
                "caption": "Perhaps you CAN be of some assistance, Bill"
            },
            "tags": [],
            "reactions": {
                "reacted": false,
                "total": 8
            },
            "status": "accepted",
            "source": null,
            "remote_addr": "207.110.19.130, 207.110.19.130",
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

