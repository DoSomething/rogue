import { RestApiClient } from '@dosomething/gateway';

class RogueClient extends RestApiClient {
  getPosts(options) {
    return this.get('posts', options);
  }

  getEvents(options) {
    return this.get('api/v2/events', options);
  }

  postReview(fields) {
    return this.post('api/v3/reviews', fields);
  }
}

export default RogueClient;
