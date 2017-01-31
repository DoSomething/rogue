# Rogue API
This is the Rogue API, it is used to capture activity from members.
All `POST` and `PUT` endpoints require an api key (`X-DS-Rogue-API-Key`) in the header to be submitted with the request. 

Refer to this [document](https://github.com/DoSomething/rogue/wiki/API) for the most updated documentation. 

## Endpoints

#### Reportbacks
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v1/reportbacks`                     | [Create a reportback](endpoints/reportbacks.md#reportbacks)

#### Reportback Items
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`PUT /api/v1/items`                            | [Reportback Items](endpoints/reportbackitems.md#reportbackitems)

#### Reactions
Endpoint                                       | Functionality                                           
---------------------------------------------- | --------------------------------------------------------
`POST /api/v1/reactions`                       | [Create or update a reaction](endpoints/reactions.md#reactions)
