## Reactions

## Create or update a Reaction v2

```
POST /api/v2/reactions
```
  - **northstar_id**: (int) required
    The northstar id of the user who "liked" or "unliked" the reportback item. 
  - **post_id**: (int) required 
    The post that the reaction belongs to. 
    
Example Request
```
curl http://rogue.dev:8000/api/v2/reactions
 -d '{
  "northstar_id":1234,
  "post_id":1,
  }'
  --header "Accept: application/json"
```

Example Response 
```
{
  "data": {
    "northstar_id": "1234",
    "post_id": "1",
    "created_at": "2017-04-03T20:17:04+00:00",
    "updated_at": "2017-04-03T20:17:04+00:00",
    "deleted_at": null,
  },
}
```
## Create or update a Reaction v3

```
POST /api/v3/posts/:post_id/reaction
```

Example Request
```
curl http://rogue.dev:8000/api/v3/posts/:post_id/reactions
 -d '{
  "northstar_id":5589c991a59dbfa93d8b45ae,
  }'
  --header "Accept: application/json"
  --X-DS-Rogue-API-Key "API_KEY"
```

Example Response
```
{
    "data": {
        "northstar_id": "5589c991a59dbfa93d8b45ae",
        "post_id": "61",
        "term": {
            "name": "heart",
            "id": 641,
            "total": 1
        },
        "created_at": "2017-10-24T14:57:40+00:00",
        "updated_at": "2017-11-03T17:00:51+00:00",
        "deleted_at": null
    },
    "meta": {
        "action": "liked",
        "total_reactions": 1
    }
}
````
