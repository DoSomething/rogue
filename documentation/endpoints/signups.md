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

Response: The signup and associated events and posts.

Example response:
```
{
  "data": {
    "signup_id": 160,
    "signup_event_id": 799,
    "submission_type": "user",
    "northstar_id": "1234",
    "campaign_id": "6",
    "campaign_run_id": "1740",
    "quantity": null,
    "quantity_pending": "200",
    "why_participated": "bcuz I luv endpointz",
    "signup_source": "phoenix-web",
    "created_at": "2017-01-19T19:11:14+0000",
    "updated_at": "2017-01-19T19:11:14+0000",
    "posts": {
      "data": [
        {
          "postable_id": 16,
          "post_event_id": 29,
          "submission_type": "user",
          "postable_type": "Rogue\\Models\\Photo",
          "content": {
            "media": {
              "url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/1334-1484771619.jpeg",
              "edited_url": "https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_1334-1484771621.jpeg"
            },
            "caption": "caption",
            "status": "pending",
            "remote_addr": null,
            "post_source": null,
            "created_at": "2017-01-18T20:33:41+0000",
            "updated_at": "2017-01-18T20:33:41+0000"
          }
        },
      ],
    },
    "events": {
      "data": [
        {
          "event_id": 800,
          "event_type": "signup",
          "submission_type": "user",
          "quantity": null,
          "quantity_pending": null,
          "why_participated": null,
          "caption": null,
          "status": null,
          "source": "chloe-testing",
          "remote_addr": null,
          "reason": null,
          "created_at": "2017-02-15T18:31:07+0000",
          "updated_at": "2017-02-15T18:31:07+0000"
        }
      ]
    }
  }
}
```
