## Signups

## Retrieve all Signups

```
GET /api/v3/signups
```
Example Response: 

```
{
  "data": [
    {
      "signup_id": 21,
      "northstar_id": "581ba6dd7f43c26c6d2349d3",
      "campaign_id": 46,
      "campaign_run_id": 3088,
      "quantity": 1100,
      "why_participated": "Rerum eos molestias blanditiis consequuntur inventore qui.",
      "signup_source": "phoenix-web",
      "details": null,
      "created_at": "2017-08-14T21:20:53+00:00",
      "updated_at": "2017-08-14T21:20:53+00:00",
      "posts": {
        "data": [
          {
            "id": 81,
            "signup_id": 21,
            "northstar_id": "581ba6dd7f43c26c6d2349d3",
            "media": {
              "url": "http://localhost/storage/uploads/reportback-items/edited_81.jpeg?time=1507749564",
              "original_image_url": "/storage//uploads/reportback-items/55349-6dba2dea32f35e93c6ec7c70e1f3ceab-1502745653.jpeg?time=1507749564",
              "caption": "Sint consequuntur perferendis eaque voluptatem iste illo impedit."
            },
            "tags": [],
            "reactions": {
              "reacted": false,
              "total": null
            },
            "status": "accepted",
            "source": "phoenix-oauth",
            "remote_addr": "247.62.90.49",
            "created_at": "2017-08-14T21:20:53+00:00",
            "updated_at": "2017-08-14T21:20:53+00:00"
          }
        ]
      }
    },
    {
      "signup_id": 22,
      "northstar_id": "574dace47f43c21f1e0d674c",
      "campaign_id": 46,
      "campaign_run_id": 8932,
      "quantity": 5023,
      "why_participated": "Nisi consequatur reiciendis ut autem.",
      "signup_source": "phoenix-web",
      "details": null,
      "created_at": "2017-08-14T21:20:53+00:00",
      "updated_at": "2017-08-14T21:20:53+00:00",
      "posts": {
        "data": [
          {
            "id": 82,
            "signup_id": 22,
            "northstar_id": "574dace47f43c21f1e0d674c",
            "media": {
              "url": "http://localhost/storage/uploads/reportback-items/edited_82.jpeg?time=1507749564",
              "original_image_url": "/storage//uploads/reportback-items/55921-676c9c02029df581d3668ae869d937b8-1502745653.jpeg?time=1507749564",
              "caption": "Eum quam incidunt enim nobis quia commodi."
            },
            "tags": [],
            "reactions": {
              "reacted": false,
              "total": null
            },
            "status": "accepted",
            "source": "phoenix-next",
            "remote_addr": "40.158.147.99",
            "created_at": "2017-08-14T21:20:53+00:00",
            "updated_at": "2017-08-14T21:20:53+00:00"
          }
        ]
      }
    },
  ],
  "meta": {
    "pagination": {
      "total": 288,
      "count": 20,
      "per_page": 20,
      "current_page": 2,
      "total_pages": 15,
      "links": {
        "previous": "http://rogue.dev/api/v3/signups?page=1",
        "next": "http://rogue.dev/api/v3/signups?page=3"
      }
    }
  }
}

```
## Create a Signup

```
POST /api/v2/signups
```

  - **northstar_id**: (string) required.
    The northstar id of the user signing up.
  - **campaign_id**: (int) required.
    The drupal node id of the campaign the user is signing up for.
  - **campaign_run_id**: (int) required.
    The drupal campaign run node id of the campaign run the user is signing up for.
  - **quantity**: (int) optional.
    The approved number of reportback nouns verbed.
  - **quantity_pending**: (int) optional.
    The pending number of reportback nouns verbed. 
  - **why_participated**: (string) optional.
    The reason why the user participated.
  - **source**: (string) optional (for migration purposes, there are signups on prod with no source).
    The source of the signup.
  - **details**: (string) optional
    Details to be added to the "details" column on the signup, such as signed up to receive affiliate messaging.
  - **created_at**: (string) optional.
    `Y-m-d H:i:s` format. When the signup was created.
  - **updated_at**: (string) optional.
    `Y-m-d H:i:s` format. When the signup was last updated.

    You may optionally include reportback photo(s)

Example request body:
```
[
  "northstar_id" => "5639066ba59dbfe6598b4567"
  "campaign_id" => "6"
  "campaign_run_id" => "1740"
  "quantity" => "200"
  "source" => "the-fox-den"
  "why_participated" => "bcuz I luv endpointz"
  "created_at" => "1991-10-21 16:50:36"
  "updated_at" => "1991-10-21 16:50:36"
  "photo" => array:2 [
    0 => array:7 [
      "source" => "photo-source"
      "remote_addr" => "testin yo"
      "caption" => "LOOK AT THAT CAT"
      "event_type" => "post_photo"
      "northstar_id" => "5639066ba59dbfe6598b4567"
      "do_not_forward" => "TRUE"
      "file" => UploadedFile {#169
        -test: false
        -originalName: "tongue-cat.jpg"
        -mimeType: "image/jpeg"
        -size: 61423
        -error: 0
        path: "/tmp"
        filename: "phppWmMAx"
        basename: "phppWmMAx"
        pathname: "/tmp/phppWmMAx"
        extension: ""
        realPath: "/tmp/phppWmMAx"
        aTime: 2017-01-19 20:20:12
        mTime: 2017-01-19 20:20:12
        cTime: 2017-01-19 20:20:12
        inode: 1700653
        size: 61423
        perms: 0100600
        owner: 900
        group: 900
        type: "file"
        writable: true
        readable: true
        executable: false
        file: true
        dir: false
        link: false
      }
    ]
    1 => array:7 [
      "source" => "second-source"
      "remote_addr" => "second home yknow?"
      "caption" => "lil chickens"
      "event_type" => "post_photo"
      "northstar_id" => "5639066ba59dbfe6598b4567"
      "do_not_forward" => "TRUE"
      "file" => UploadedFile {#171
        -test: false
        -originalName: "chickens.png"
        -mimeType: "image/png"
        -size: 612306
        -error: 0
        path: "/tmp"
        filename: "phpfhZAf7"
        basename: "phpfhZAf7"
        pathname: "/tmp/phpfhZAf7"
        extension: ""
        realPath: "/tmp/phpfhZAf7"
        aTime: 2017-01-19 20:20:12
        mTime: 2017-01-19 20:20:12
        cTime: 2017-01-19 20:20:12
        inode: 1700654
        size: 612306
        perms: 0100600
        owner: 900
        group: 900
        type: "file"
        writable: true
        readable: true
        executable: false
        file: true
        dir: false
        link: false
      }
    ]
  ]
]
```

Response: The signup and associated events and posts.

Example response:
```
{
  "data": {
    "signup_id": 160,
    "northstar_id": "5571df46a59db12346dsb456d",
    "campaign_id": "6",
    "campaign_run_id": "1740",
    "quantity": null,
    "quantity_pending": "200",
    "why_participated": "bcuz I luv endpointz",
    "signup_source": "phoenix-web",
    "details": "affiliate-messaging",
    "created_at": "2017-01-19T19:11:14+0000",
    "updated_at": "2017-01-19T19:11:14+0000",
    "posts": {
      "data": [
        {
          "id": 340,
          "signup_id": 160,
          "northstar_id": "5571df46a59db12346dsb456d",
          "media": {
            "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/18-1487182498.jpeg",
            "caption": "Captioning captions",
          },
          "status": "pending",
          "remote_addr": "207.110.19.130",
          "post_source": "runscope",
          "created_at": "2017-02-15T18:14:58+0000",
          "updated_at": "2017-02-15T18:14:58+0000",
        },
      ],
    },
  }
}
```
