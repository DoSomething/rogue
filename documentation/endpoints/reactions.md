## Reactions

Create or update a Reaction 

```
POST /api/v2/reactions
```
  - **northstar_id**: (int) required
    The northstar id of the user who "liked" or "unliked" the reportback item. 
  - **reactionable_id**: (int) required 
    The post that the reaction belongs to. 
  - **reactionable_type**: (string) required
    The post type that the reaction belongs to.
    
Example Request
```
curl http://rogue.dev:8000/api/v2/reactions
 -d '{
  "northstar_id":1234,
  "reactionable_id":1,
  "reactionable_type":"Rogue\Models\Photo",
  }'
  --header "Accept: application/json"
```
Example Response 
```
{
  "data": {
    "reaction_id": "29",
    "northstar_id": "1234",
    "reactionable_id": "1",
    "reactionable_type": "Rogue\\Models\\Photo",
    "created_at": {
      "date": "2017-03-14 20:58:07.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "updated_at": {
      "date": "2017-03-15 15:23:43.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "deleted_at": {
      "date": "2017-03-15 15:26:57.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    }
  },
  "meta": {
    "action": "unliked",
    "total_reactions": 2
  }
}
```
