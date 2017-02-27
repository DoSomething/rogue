## Reactions

Retrieve all Reaction data 

```
GET /api/v2/reactions
```

Example Response:

```
{
  "data": [
    {
      "reaction_id": "1",
      "northstar_id": "1234",
      "postable_id": "4",
      "postable_type": "Rogue\\Models\\Photo",
      "created_at": {
        "date": "2016-10-12 17:31:41.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "updated_at": {
        "date": "2016-10-12 17:31:41.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "deleted_at": null,
      "total_reactions": 1
    },
    {
      "reaction_id": "2",
      "northstar_id": "12345",
      "postable_id": "6",
      "postable_type": "Rogue\\Models\\Photo",
      "created_at": {
        "date": "2016-10-12 17:31:41.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "updated_at": {
        "date": "2016-10-12 17:31:41.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "deleted_at": null,
      "total_reactions": 4
    },
    {
      "reaction_id": "3",
      "northstar_id": "12345678",
      "postable_id": "9",
      "postable_type": "Rogue\\Models\\Photo",
      "created_at": {
        "date": "2016-10-12 17:31:41.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "updated_at": {
        "date": "2016-10-12 17:31:41.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "deleted_at": null,
      "total_reactions": 1
    },
  "meta": {
    "pagination": {
      "total": 3,
      "count": 3,
      "per_page": 20,
      "current_page": 1,
      "total_pages": 1,
      "links": []
    }
  }
}
```

Create or update a Reaction 

```
POST /api/v1/reactions
```
  - **northstar_id**: (int) required
    The northstar id of the user who "liked" or "unliked" the reportback item. 
  - **postable_id**: (int) required 
    The post that the reaction belongs to. 
  - **postable_type**: (string) required
    The post type of the post.
    
Example Request
```
curl http://rogue.dev:8000/api/v2/reactions
 -d '{
  "northstar_id":1234,
  "postable_id":1,
  "postable_type":"Rogue\Models\Photo",
  }'
  --header "Accept: application/json"
```
Example Response 
```
{
  "data": {
    "reaction_id": "22",
    "northstar_id": "8888",
    "postable_id": "352",
    "postable_type": "Rogue\\Models\\Photo",
    "created_at": {
      "date": "2017-02-27 19:33:14.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "updated_at": {
      "date": "2017-02-27 19:33:14.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "deleted_at": {
      "date": "2017-02-27 19:33:56.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "total_reactions": 1
  },
  "meta": {
    "action": "unliked"
  }
}
```
