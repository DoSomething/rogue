/* eslint-disable class-methods-use-this */
import { RestApiClient } from '@dosomething/gateway';
import Posts from '../../_mocks_/__mockData__/posts.json';

class RogueClient extends RestApiClient {
  getPosts() {
    return new Promise((resolve, reject) => {
      const error = false;

      if (error) {
        reject(Error('It broke'));
      }

      resolve(Posts);
    });
  }
}

export default RogueClient;
