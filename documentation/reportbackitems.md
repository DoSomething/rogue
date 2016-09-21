## ReportbackItems

Update a ReportbackItem 

```
PUT /api/v1/items
```
**Right now, this is only built out to update a reportback item's status. 

  - **rogue_reportback_item_id**: (string) required
    The reportback item's Rogue id (id column in Rogue's reportback_items table).
  - **status**: (string)
    The status of the reportback item. 
