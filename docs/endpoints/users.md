## Posts

All `v3 /users` endpoints require the `activity` scope.

## Delete a user's activity

This endpoint will delete the given user's activity. It is used in the account deletion process.

Requires the `activity` and `write` scopes, and admin or staff role.

```
DELETE /api/v3/users/:user_id
```

Example Response:

```
{
    "code": 200,
    "message": "All signups & posts deleted."
}
```
