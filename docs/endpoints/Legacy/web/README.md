### Web

Web endpoints are used by the Rogue Admin Interface built in React.

(add notes about how web endpoints are authenticated differently and why.)

#### Endpoints

| Resource  | Functionality                      |
| --------- | ---------------------------------- |
| `reviews` | [Update a post's status](#reviews) |

## Reviews - WEB

Update a post's status when an admin reviews the post.

```
PUT /reviews
```

- **post_id**: (string) required.
  The id of the post that has been reviewed.
- **status**: (string) required.
  The status of the post.
- **comment**: (string)
  A comment that the reviewer has made.

Example Response:

```
{
  "data": {
    "id": 1,
    "signup_id": 1,
    "northstar_id": "1234",
    "media": {
      "url": "https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg",
      "caption": "Est blanditiis ab quo sequi quis."
    },
    "status": "approved",
    "source": "phoenix-web",
    "remote_addr": "0.0.0.0",
    "created_at": "2017-04-28T20:14:49+00:00",
    "updated_at": "2017-04-28T20:22:23+00:00"
  }
}
```
