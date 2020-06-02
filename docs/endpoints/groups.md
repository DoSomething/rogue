## Groups

### Index

```
GET /api/v3/groups
```

Example Response:

```
{
  "data": [
    {
      "id": 12,
      "group_type_id": 1,
      "name": "New York",
      "goal": 200,
      "created_at": "2019-12-04T21:28:26+00:00",
      "updated_at": "2019-12-04T22:33:03+00:00"
    },
    {
      "id": 23,
      "group_type_id": 1,
      "name": "San Francisco",
      "goal": null,
      "created_at": "2019-12-04T22:05:29+00:00",
      "updated_at": "2019-12-04T22:05:29+00:00"
    }
  ],
  "meta": {
    "pagination": {
      "total": 2,
      "count": 2,
      "per_page": 20,
      "current_page": 1,
      "total_pages": 1,
      "links": [

      ]
    }
  }
```

### Show

```
GET /api/v3/groups/:group_id
```

Example Response:

```
{
  "data": {
    "id": 12,
    "group_type_id": 1,
    "name": "New York",
    "goal": 200,
    "created_at": "2019-12-04T21:28:26+00:00",
    "updated_at": "2019-12-04T22:33:03+00:00"
  };
}
```
