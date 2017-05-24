## Tags

Add or delete a post's tags. 

```
POST /tags
```

  - **post_id**: (string) required.
    The id of the post that has been reviewed.
  - **tag_name**: (string) required.
    The tag that is being added or deleted from a post.

Example Response:

```
Object {id: 1, signup_id: 1, northstar_id: "1234", url: "https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg", caption: "post 1"…}
caption: "post 1"
created_at: "2017-04-28 20:14:49"
deleted_at: null
id: 1
northstar_id: "1234"
remote_addr: "10.0.2.2"
signup: Object
signup_id: 1
source: "phoenix-web"
status: "accepted"
tagged: Array(2)
updated_at: "2017-05-16 17:39:29"
url: "https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg"
user: Object
__proto__: Object
```
