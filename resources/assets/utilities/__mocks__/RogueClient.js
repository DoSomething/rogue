/* eslint-disable class-methods-use-this */
import { RestApiClient } from '@dosomething/gateway';
import Posts from '../../_mocks_/__mockData__/posts.json';
import Events from '../../_mocks_/__mockData__/events.json';

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

  getEvents() {
    return new Promise((resolve, reject) => {
      const error = false;

      if (error) {
        reject(Error('It broke'));
      }

      resolve(Events);
    });
  }
}

export default RogueClient;
