## Signups

Create a Signup

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
  - **remote_addr**: (string).
    IP address of where the reportback is submitted from.
  - **do_not_forward**: (string) optional.
    TRUE if the signup should not be posted back to Phoenix (so during the migration).
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

Response: The signup and associated event and posts.

Example response:
```
{
  "data": {
    "id": 160,
    "northstar_id": "5639066ba59dbfe6598b4567",
    "campaign_id": "6",
    "campaign_run_id": "1740",
    "quantity": null,
    "quantity_pending": "200",
    "why_participated": "bcuz I luv endpointz",
    "created_at": "2017-01-19T19:11:14+0000",
    "updated_at": "2017-01-19T19:11:14+0000",
    "post": {
      "data": [
        {
          "signup_id": 160,
          "northstar_id": "5639066ba59dbfe6598b4567",
          "campaign_id": 6,
          "campaign_run_id": 1740,
          "content": {
            "type": "post_photo",
            "media": {
              "url": "https://s3.amazonaws.com/ds-rogue-qa/uploads/reportback-items/160-1484853074.jpeg",
              "edited_url": null
            },
            "caption": "LOOK AT THAT CAT",
            "status": "pending",
            "created_at": "2017-01-19T19:11:15+0000",
            "updated_at": "2017-01-19T19:11:15+0000"
          },
          "event": {
            "data": {
              "event_id": "290",
              "event_type": "post_photo",
              "submission_type": "",
              "created_at": "2017-01-19T19:11:14+0000",
              "updated_at": "2017-01-19T19:11:14+0000"
            }
          }
        },
        {
          "signup_id": 160,
          "northstar_id": "5639066ba59dbfe6598b4567",
          "campaign_id": 6,
          "campaign_run_id": 1740,
          "content": {
            "type": "post_photo",
            "media": {
              "url": "https://s3.amazonaws.com/ds-rogue-qa/uploads/reportback-items/160-1484853075.png",
              "edited_url": null
            },
            "caption": "lil chickens",
            "status": "pending",
            "created_at": "2017-01-19T19:11:16+0000",
            "updated_at": "2017-01-19T19:11:16+0000"
          },
          "event": {
            "data": {
              "event_id": "291",
              "event_type": "post_photo",
              "submission_type": "",
              "created_at": "2017-01-19T19:11:15+0000",
              "updated_at": "2017-01-19T19:11:15+0000"
            }
          }
        }
      ]
    },
    "event": {
      "data": {
        "event_id": "289",
        "event_type": "signup",
        "submission_type": "user",
        "created_at": "2017-01-19T19:11:14+0000",
        "updated_at": "2017-01-19T19:11:14+0000"
      }
    }
  }
}
```
