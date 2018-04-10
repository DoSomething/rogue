import { RestApiClient } from '@dosomething/gateway';

class RogueClient extends RestApiClient {
  getPosts(options) {
    return this.get('api/v3/posts', options);
  }

  getEvents(options) {
    return this.get('api/v2/events', options);
  }

  postReview(fields) {
    return this.put('reviews', fields);
  }
}

export default RogueClient;
