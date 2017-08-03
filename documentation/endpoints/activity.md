## Activity

Retrieve all user activity data. 

```
GET /api/v2/activity
```
### Optional Query Parameters
- **id** _(integer)_
  - The signup id(s) to filter the response by.
  - e.g. `/activity?filter[id]=121,122`
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
- **northstar_id** _(integer)_
  - The northstar id(s) to filter the response by.
  - e.g. `/activity?filter[northstar_id]=1234`
- **updated_at** _(timestamp)_
  - Return records that have been updated at after the updated_at param value. 
  - e.g. `/activity?filter[updated_at]=2017-05-25 20:14:48`


Example Response:

```
{
  "data": [
    {
      "signup_id": 15,
      "northstar_id": "1234",
      "campaign_id": 47,
      "campaign_run_id": 1771,
      "quantity": null,
      "why_participated": "because i love to help!",
      "signup_source": "phoenix-web",
      "created_at": "2017-01-20T20:26:56+0000",
      "updated_at": "2017-01-20T20:26:56+0000",
      "posts": {
        "data": [
          {
            "id": 340,
            "signup_id": 15,
            "northstar_id": "1234",
            "media": {
              "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/18-1487182498.jpeg",
              "caption": "Captioning captions",
            },
            "tagged": [
              "good-photo",
              "good-quote"
            ],
            "reactions": [
              {
                  "id": 31,
                  "northstar_id": "1234",
                  "post_id": 340,
                  "created_at": "2017-05-11 19:52:21",
                  "updated_at": "2017-05-11 20:56:02",
                  "deleted_at": null
              }
            ],
            "status": "pending",
            "source": "phoenix-web",
            "remote_addr": "207.110.19.130",
            "created_at": "2017-02-15T18:14:58+0000",
            "updated_at": "2017-02-15T18:14:58+0000"
          },
          {
            "id": 312,
            "signup_id": 15,
            "northstar_id": "12345",
            "media": {
              "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/18-1487182498.jpeg",
              "caption": "Captioning captions",
            },
            "tagged": [],
            "reactions": [],
            "status": "pending",
            "source": "phoenix-web",
            "remote_addr": "207.110.19.130",
            "created_at": "2017-02-11T18:14:58+0000",
            "updated_at": "2017-02-11T18:14:58+0000"
          },
        ]
      },
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
