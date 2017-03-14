## Reactions

Create or update a Reaction 

```
POST /api/v2/reactions
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
{
  "data": {
    "reaction_id": "21",
    "northstar_id": "8888",
    "postable_id": "352",
    "postable_type": "Rogue\\Models\\Photo",
    "created_at": {
      "date": "2017-02-24 16:10:01.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "updated_at": {
      "date": "2017-02-24 16:11:07.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    },
    "deleted_at": {
      "date": "2017-02-24 16:11:14.000000",
      "timezone_type": 3,
      "timezone": "UTC"
    }
  },
  "meta": {
    "action": "unliked",
    "total_reactions": 1
  }
}
```
