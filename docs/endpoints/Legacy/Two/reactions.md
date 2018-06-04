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
