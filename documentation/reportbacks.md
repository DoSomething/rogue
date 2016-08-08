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
  - **why_participated**: (string) required.
    The reason why the user participated.
  - **file_id**: (int) required.
    The drupal file id of the reportback item to submit with this reportback. 
  - **caption**: (string).
    Corresponding caption for the reportback image.
  - **source**: (string).
    Where the reportback file was submitted from.

Response: The reportback and associated reportback items.
