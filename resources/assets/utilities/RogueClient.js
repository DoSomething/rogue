import { RestApiClient } from '@dosomething/gateway';

class RogueClient extends RestApiClient {
  getPosts(options) {
    return this.get('posts', options);
  }
}

export default RogueClient;
