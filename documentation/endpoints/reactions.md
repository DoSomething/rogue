## Reactions

Create or update a Reaction 

```
POST /api/v1/reactions
```
  - **northstar_id**: (int) required
    The northstar id of the user who "liked" or "unliked" the reportback item. 
  - **reportback_item_id**: (int) required 
    The reportback item that the reaction belongs to. 
    
Example Request
```
curl http://rogue.dev:8000/api/v1/reactions
 -d '{
  "northstar_id":1234,
  "reportback_item_id":1
  }'
  --header "Accept: application/json"
```
Example Response 
```
{
  "data": {
    "reaction_id": 11,
    "northstar_id": "1234",
    "reportback_item_id": 1,
    "created_at": {
      "date": "2016-10-12 17:31:41.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "updated_at": {
      "date": "2016-10-21 20:03:28.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "deleted_at": {
      "date": "2016-10-21 20:04:07.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    }
  },
  "meta": {
    "action": "liked"
  }
}
```
