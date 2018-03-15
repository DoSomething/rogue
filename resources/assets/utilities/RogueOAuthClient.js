import { RestApiClient } from '@dosomething/gateway';

class RogueOAuthClient extends RestApiClient {
  postReview(fields) {
    return this.put('reviews', fields);
  }
}

export default RogueClient;
