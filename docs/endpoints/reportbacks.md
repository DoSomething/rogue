## Reportbacks

Retrieve all Posts.

```
GET /api/v1/reportbacks
```

### Optional Query Parameters
- **limit**
  - Set the number of records to return in a single response.
  - e.g. `/reportbacks?limit=35`
- **page** _(integer)_
  - For pagination, specify page of activity to return in the response.
  - e.g. `/reportbacks?page=2`
- **campaign_id** _(integer)_
  - The nid to filter the response by.
  - e.g. `/reportbacks?filter[campaign_id]=47`
- **northstar_id** _(integer)_
  - The northstar_id to filter the response by.
  - e.g. `/reportbacks?filter[northstar_id]=47asdf23abc`
- **status** _(string)_
  - The string to filter the response by.
  - e.g. `/reportbacks?filter[status]=accepted`
- **exclude** _(integer)_
  - The post id(s) to exclude in response. 
  - e.g. `/reportbacks?filter[exclude]=2,3,4`
- **as_user** _(string)_
  - The logged in user to display if they have reacted to the post or not.
  - e.g. `/reportbacks?as_user=1234`

Example Response:

```
{
    "data": [
        {
            "id": "2",
            "status": "accepted",
            "caption": "post 2",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/2/1",
            "media": {
                "uri": "default",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "2|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 1
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "3",
            "status": "accepted",
            "caption": "post 3",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/3/1",
            "media": {
                "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_3.jpeg",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "3|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 2
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "4",
            "status": "accepted",
            "caption": "post 4",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/4/1",
            "media": {
                "uri": "default",
                "type": "image"
            },
            "tagged": [
                "Good Submission",
                "Good For Storytelling"
            ],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "4|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 2
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "7",
            "status": "accepted",
            "caption": "post 7",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/7/1",
            "media": {
                "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_7.jpeg",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "7|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 1
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "8",
            "status": "accepted",
            "caption": "post 8",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/8/1",
            "media": {
                "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_8.jpeg",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "8|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 1
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "9",
            "status": "accepted",
            "caption": "post 9",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/9/1",
            "media": {
                "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_9.jpeg",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "9|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 2
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "10",
            "status": "accepted",
            "caption": "post 10",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/10/1",
            "media": {
                "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_10.jpeg",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "10|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 1
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "11",
            "status": "accepted",
            "caption": "post 11",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/11/1",
            "media": {
                "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_11.jpeg",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": null,
                            "reacted": false
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 0
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        },
        {
            "id": "12",
            "status": "accepted",
            "caption": "post 12",
            "uri": "http://rogue.dev:8000/dev.dosomething.org:8888/api/v1/reportback-items/12/1",
            "media": {
                "uri": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_12.jpeg",
                "type": "image"
            },
            "tagged": [],
            "created_at": "1493410489",
            "reportback": {
                "id": "1",
                "created_at": "2017-04-28T20:14:48+00:00",
                "updated_at": "2017-07-24T15:16:49+00:00",
                "quantity": 30,
                "why_participated": "test",
                "flagged": "false"
            },
            "campaign": {
                "id": 1283
            },
            "kudos": {
                "data": [
                    {
                        "current_user": {
                            "kudos_id": "12|1234",
                            "reacted": true
                        },
                        "term": {
                            "id": "641",
                            "name": "heart",
                            "total": 1
                        }
                    }
                ]
            },
            "user": {
                "id": "1234"
            }
        }
    ],
    "meta": {
        "pagination": {
            "total": 9,
            "count": 9,
            "per_page": 20,
            "current_page": 1,
            "total_pages": 1,
            "links": []
        }
    }
}
```
