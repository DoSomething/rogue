## Reportbacks

Create a Reportback

```
POST /api/v1/reportbacks
```

  - **northstar_id**: (string) required if `drupal_id` is not provided.
    The northstar id of the user reporting back.
  - **drupal_id**: (int) required if `northstar_id` is not provided.
    The drupal user id of the user reporting back.
  - **campaign_id**: (int) required.
    The drupal node id of the campaign the user is reporting back on.
  - **campaign_run_id**: (int) required.
    The drupal campaign run node id of the campaign run the user is reporting back on.
  - **quantity**: (int).
    The number of reportback nouns verbed. 
  - **why_participated**: (string) required.
    The reason why the user participated.
  - **num_participants**: (int).
    How many people participated in the action. 
  - **caption**: (string).
    Corresponding caption for the reportback image.
  - **source**: (string).
    Where the reportback file was submitted from.
  - **remote_addr**: (string).
    IP address of where the reportback is submitted from. 
  - **file**: (string) required.
    File string to save of reportback image.

Response: The reportback and associated reportback items.
