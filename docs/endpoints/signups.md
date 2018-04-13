## Signups
All `v3 /signups` endpoints require the `activity` scope. `Create`/`update`/`delete` endpoints also require the `write` scope.

## Create a Signup

```
POST /api/v3/signups
```
  - **campaign_id**: (int|string) required.
    The drupal node id of the campaign the user is signing up for.
  - **campaign_run_id**: (int) optional.
    The drupal campaign run node id of the campaign run the user is signing up for.
  - **why_participated**: (string) optional.
    The reason why the user participated.
  - **source**: (string) optional (for migration purposes, there are signups on prod with no source).
    The source of the signup.
  - **details**: (string) optional
    Details to be added to the "details" column on the signup, such as signed up to receive affiliate messaging.
  - **dont_send_to_blink** (boolean) optional.
    If included and true, the data for this Signup will not be sent to Blink.
  - **created_at**: (string) optional.
    `Y-m-d H:i:s` format. When the signup was created.
  - **updated_at**: (string) optional.
    `Y-m-d H:i:s` format. When the signup was last updated.

Example response:
```
{
    "data": {
        "id": 354,
        "northstar_id": "5589c991a59dbfa93d8b45ae",
        "campaign_id": "362",
        "campaign_run_id": null,
        "quantity": null,
        "created_at": "2017-12-01T19:48:16+00:00",
        "updated_at": "2017-12-01T19:48:16+00:00"
        "why_participated": "to test",
        "source": null,
        "details": null,
    }
}
```

## Retrieve all Signups

```
GET /api/v3/signups
```

Only admins and signup owners will have `why_participated`, `source`, and `details` returned in the response.

When using `?include=posts`, anonymous requests will only return accepted posts. Logged-in users can see accepted posts & any of their own pending or rejected posts. Staff can see anything!

### Optional Query Parameters
- **include** _(string)_
  - Include additional related records in the response: `posts`, `user`, `accepted_quantity`
  - If using multiple include params, they must be comma-separated
  - e.g. `/api/v3/signups/1?include=user,posts`
- **filter[column]** _(string)_
  - Filter results by the given column: `northstar_id`, `campaign_id`, `campaign_run_id`
  - You can filter by more than one column, e.g. `/signups?filter[id]=4&filter[campaign_id]=5`
  - You can filter by more than one value for a column, e.g. `/signups?filter[campaign_id]=121,122`
- **orderBy** _(string)_
  - Determines order the results are returned, based on the signups's index and direction.
  - e.g. `/signups?orderBy=quantity,desc`
  - e.g. `/signups?orderBy=quantity,asc`
- **limit** _(default is 20)_
  - Set the number of records to return in a single response.
  - e.g. `/signups?limit=35`
- **page** _(integer)_
  - For pagination, specify page of signups to return in the response.
  - e.g. `/signups?page=2`

Example Response:

```
{
  "data": [
    {
      "id": 1,
      "northstar_id": "5589c991a59dbfa93d8b45ae",
      "campaign_id": 1173,
      "campaign_run_id": 6519,
      "quantity": null,
      "created_at": "2017-08-14T21:20:51+00:00",
      "updated_at": "2017-08-15T16:03:25+00:00"
      "why_participated": "Eos architecto et quibusdam quasi.",
      "source": "phoenix-web",
      "details": null,
    },
    {
      "id": 2,
      "northstar_id": "5589c991a59dbfa93d8b45ae",
      "campaign_id": 1631,
      "campaign_run_id": 1626,
      "quantity": null,
      "created_at": "2017-08-14T21:20:51+00:00",
      "updated_at": "2017-08-30T19:08:25+00:00"
      "why_participated": "Non nobis ab asperiores fuga.",
      "source": "phoenix-web",
      "details": null,
    },
  ],
  "meta": {
    "pagination": {
      "total": 288,
      "count": 20,
      "per_page": 20,
      "current_page": 1,
      "total_pages": 15,
      "links": {
        "next": "http://rogue.dev/api/v3/signups?page=2"
      }
    }
  }
}

```
## Retrieve a specific signup

```
GET /api/v3/signups/:signup_id
```

Only admins and signup owners will have `why_participated`, `source`, and `details` returned in the response.

When using `?include=posts`, anonymous requests will only return accepted posts. Logged-in users can see accepted posts & any of their own pending or rejected posts. Staff can see anything!
### Optional Query Parameters
- **include** _(string)_
  - Include additional related records in the response: `posts`, `user`
  - If using multiple include params, they must be comma-separated
  - e.g. `/api/v3/signups/1?include=user,posts`

Example Response:

```
{
  "data": {
    "id": 1,
    "northstar_id": "5589c991a59dbfa93d8b45ae",
    "campaign_id": 1173,
    "campaign_run_id": 6519,
    "quantity": null,
    "created_at": "2017-08-14T21:20:51+00:00",
    "updated_at": "2017-08-15T16:03:25+00:00",
    "why_participated": "Eos architecto et quibusdam quasi.",
    "source": "phoenix-web",
    "details": null,
    "posts": {
      "data": []
    }
  }
}
```

## Update a Signup

```
PATCH /api/v3/signups/:signup_id
```

  - **why_participated**: (string) required.
    The reason why the user participated.

Example response:
```
{
    "data": {
        "id": 352,
        "northstar_id": "5589c991a59dbfa93d8b45ae",
        "campaign_id": "362",
        "campaign_run_id": null,
        "quantity": null,
        "created_at": "2017-12-01T16:39:03+00:00",
        "updated_at": "2017-12-01T19:24:22+00:00"
        "why_participated": "to test update",
        "source": null,
        "details": null,
    }
}
```

## Delete a Signup

```
DELETE /api/v3/signups/:signup_id
```
Example Response:

```
{
    "code": 200,
    "message": "Signup deleted."
}

```
