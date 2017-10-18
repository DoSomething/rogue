## Activity

Retrieve all user activity data. 

```
GET /api/v2/activity
```
### Optional Query Parameters
- **filter[column]** _(integer)_
  - Filter results by the given column: `id`, `campaign_id`, `campaign_run_id`, `northstar_id`
  - You can filter by more than one column, e.g. `/activity?filter[id]=4&filter[campaign_id]=5`
  - You can filter by more than one value for a column, e.g. `/activity?filter[id]=121,122`
- **limit** _(default is 20)_
  - Set the number of records to return in a single response.
  - e.g. `/activity?limit=35`
- **page** _(integer)_
  - For pagination, specify page of activity to return in the response.
  - e.g. `/activity?page=2`
- **include** _(string)_
  - Include additional related records in the response: `user`
  - e.g. `/activity?include=user`
- **orderBy** _(string)_
  - Determines order the results are returned, based on the signups's created_at timestamp.
  - If 'desc' is not passed through, defaults to return results by signup's created_at by ascending order. 
  - e.g. `/activity'?orderByPost=desc`
- **filter[updated_at]** _(timestamp)_
  - Return records that have been updated after the given `updated_at` value. 
  - e.g. `/activity?filter[updated_at]=2017-05-25 20:14:48`
- **pagination** _(string)_
  - If set to 'cursor' we use Laravel's `simplePaginate` to paginate the results which will return less meta data on each request, if it is not passed it will use regular pagination.
  - e.g. `/activity?pagination=cursor`

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
      "details": "affiliate-messaging",
      "created_at": "2017-01-20T20:26:56+0000",
      "updated_at": "2017-01-20T20:26:56+0000",
      "posts": {
        "data": [
          {
            "id": 340,
            "signup_id": 15,
            "northstar_id": "1234",
            "media": {
              "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_214.jpeg",
              "original_image_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/128-482cab927f6529c7f5e5c4bfd2594186-1501090354.jpeg",
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
    "cursor": {
        "current": 1,
        "prev": null,
        "next": "http://rogue.app/api/v2/activity?page=2",
        "count": 20
    }
  }
}
```
