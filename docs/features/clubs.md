# Clubs

DoSomething Clubs are a social group of DoSomething members engaging with our platform to affect positive change as a formalized cohesive group. (Or dare we say, a club!)

## Creating a club

Clubs can be created by visiting the clubs creation page: https://activity.dosomething.org/clubs/create.

**Fields**:

- **Name**: The name of the club
- **Leader ID**: The valid Northstar ID of the club leader (must be unique).
- **City** (_optional_): The city where the club is located
- **Location** (_optional_): The location of the club in [ISO 3166-2](https://www.iso.org/obp/ui/#iso:code:3166:US) format (e.g. US-NY).
- **School ID** (_optional_): The Great Schools universal ID for the school the Club is associated with.

## Editing a club

Clubs can be edited by visiting the edit page using the Club ID e.g. https://activity.dosomething.org/clubs/1/edit

## Viewing a club

The full list of clubs can be viewed at https://activity.dosomething.org/clubs. Individual clubs via appending the ID, e.g. https://activity.dosomething.org/clubs/1.

## Updating a user's club

You can view and update a user's `club_id` field via their profile page on our [admin site](https://admin.dosomething.org/users).

## Joining a club

Over on our website, users can join a club via an embedded [Current Club Block](https://dosomething.gitbook.io/phoenix-documentation/development/content-types/current-club-block).

## Tracking Impact

Any Signups or Posts created for a user associated with a club will track the Clubs ID in the `club_id` field of the Signup/Post.

Rogue will check for a user's `club_id` in Northstar via GraphQL before saving a signup. For posts, since a signup must _precede_ a post, we can check for the post's signup's `club_id` field and if found, assign this to the post's `club_id` field.
