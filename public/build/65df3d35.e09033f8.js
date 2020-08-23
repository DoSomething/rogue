(window.webpackJsonp = window.webpackJsonp || []).push([
  [14],
  {
    68: function(t, e, a) {
      'use strict';
      a.r(e),
        a.d(e, 'frontMatter', function() {
          return l;
        }),
        a.d(e, 'metadata', function() {
          return i;
        }),
        a.d(e, 'rightToc', function() {
          return c;
        }),
        a.d(e, 'default', function() {
          return O;
        });
      var n = a(2),
        b = a(6),
        r = (a(0), a(88)),
        l = { title: 'API Overview', sidebar_label: 'Overview' },
        i = {
          unversionedId: 'api/overview',
          id: 'api/overview',
          isDocsHomePage: !1,
          title: 'API Overview',
          description:
            'These endpoints use OAuth 2 to authenticate. More information here',
          source: '@site/docs/api/overview.md',
          permalink: '/build/docs/api/overview',
          editUrl:
            'https://github.com/facebook/docusaurus/edit/master/website/docs/api/overview.md',
          sidebar_label: 'Overview',
          sidebar: 'sidebar',
          previous: {
            title: 'Contributing',
            permalink: '/build/docs/development/contributing',
          },
          next: { title: 'Style Guide', permalink: '/build/docs/' },
        },
        c = [
          { value: 'Signups', id: 'signups', children: [] },
          { value: 'Posts', id: 'posts', children: [] },
          { value: 'Reactions', id: 'reactions', children: [] },
          { value: 'Reviews', id: 'reviews', children: [] },
          { value: 'Tags', id: 'tags', children: [] },
          { value: 'Events', id: 'events', children: [] },
          { value: 'Campaigns', id: 'campaigns', children: [] },
          { value: 'Actions', id: 'actions', children: [] },
          { value: 'Action Stats', id: 'action-stats', children: [] },
          { value: 'Groups', id: 'groups', children: [] },
          { value: 'Group Types', id: 'group-types', children: [] },
          { value: 'Users', id: 'users', children: [] },
        ],
        p = { rightToc: c };
      function O(t) {
        var e = t.components,
          a = Object(b.a)(t, ['components']);
        return Object(r.b)(
          'wrapper',
          Object(n.a)({}, p, a, { components: e, mdxType: 'MDXLayout' }),
          Object(r.b)(
            'p',
            null,
            'These endpoints use OAuth 2 to authenticate. ',
            Object(r.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                {
                  href:
                    'https://github.com/DoSomething/northstar/blob/master/documentation/authentication.md',
                },
              ),
              'More information here',
            ),
          ),
          Object(r.b)('h2', { id: 'signups' }, 'Signups'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'POST /api/v3/signups',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Create a signup',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/signups',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get signups',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/signups/:signup_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get a signup',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'PATCH /api/v3/signups/:signup_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Update a signup',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'DELETE /api/v3/signups/:signup_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Delete a signup',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'posts' }, 'Posts'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'POST /api/v3/posts',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Create a post',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/posts',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get posts',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/posts/:post_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get a post',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'DELETE /api/v3/posts/:post_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Delete a post',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'PATCH /api/v3/posts/:post_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Update a post',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'reactions' }, 'Reactions'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'POST /api/v3/posts/:post_id/reactions',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Create or update a Reaction',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/posts/:post_id/reactions',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get all reactions of a post',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'reviews' }, 'Reviews'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'POST /api/v3/posts/:post_id/reviews',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Create or update a Review',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'tags' }, 'Tags'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'POST /api/v3/posts/:post_id/tags',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Tag or Untag a Post',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'events' }, 'Events'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/events',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get all events',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'campaigns' }, 'Campaigns'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/campaigns',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get campaigns',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/campaigns/:campaign_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get a campaign',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'actions' }, 'Actions'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/actions',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get actions',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/actions/:action_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get an action',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'action-stats' }, 'Action Stats'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/action-stats',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get action stats',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'groups' }, 'Groups'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/groups',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get groups',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/groups/:group_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get a group',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'group-types' }, 'Group Types'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/group-types',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get group types]',
                ),
              ),
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'GET /api/v3/group-types/:group_type_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Get a group type]',
                ),
              ),
            ),
          ),
          Object(r.b)('h2', { id: 'users' }, 'Users'),
          Object(r.b)(
            'table',
            null,
            Object(r.b)(
              'thead',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'thead' },
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Endpoint',
                ),
                Object(r.b)(
                  'th',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  'Functionality',
                ),
              ),
            ),
            Object(r.b)(
              'tbody',
              { parentName: 'table' },
              Object(r.b)(
                'tr',
                { parentName: 'tbody' },
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  Object(r.b)(
                    'inlineCode',
                    { parentName: 'td' },
                    'DELETE /api/v3/users/:user_id',
                  ),
                ),
                Object(r.b)(
                  'td',
                  Object(n.a)({ parentName: 'tr' }, { align: null }),
                  "Delete a user's activity",
                ),
              ),
            ),
          ),
        );
      }
      O.isMDXComponent = !0;
    },
    88: function(t, e, a) {
      'use strict';
      a.d(e, 'a', function() {
        return o;
      }),
        a.d(e, 'b', function() {
          return u;
        });
      var n = a(0),
        b = a.n(n);
      function r(t, e, a) {
        return (
          e in t
            ? Object.defineProperty(t, e, {
                value: a,
                enumerable: !0,
                configurable: !0,
                writable: !0,
              })
            : (t[e] = a),
          t
        );
      }
      function l(t, e) {
        var a = Object.keys(t);
        if (Object.getOwnPropertySymbols) {
          var n = Object.getOwnPropertySymbols(t);
          e &&
            (n = n.filter(function(e) {
              return Object.getOwnPropertyDescriptor(t, e).enumerable;
            })),
            a.push.apply(a, n);
        }
        return a;
      }
      function i(t) {
        for (var e = 1; e < arguments.length; e++) {
          var a = null != arguments[e] ? arguments[e] : {};
          e % 2
            ? l(Object(a), !0).forEach(function(e) {
                r(t, e, a[e]);
              })
            : Object.getOwnPropertyDescriptors
            ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(a))
            : l(Object(a)).forEach(function(e) {
                Object.defineProperty(
                  t,
                  e,
                  Object.getOwnPropertyDescriptor(a, e),
                );
              });
        }
        return t;
      }
      function c(t, e) {
        if (null == t) return {};
        var a,
          n,
          b = (function(t, e) {
            if (null == t) return {};
            var a,
              n,
              b = {},
              r = Object.keys(t);
            for (n = 0; n < r.length; n++)
              (a = r[n]), e.indexOf(a) >= 0 || (b[a] = t[a]);
            return b;
          })(t, e);
        if (Object.getOwnPropertySymbols) {
          var r = Object.getOwnPropertySymbols(t);
          for (n = 0; n < r.length; n++)
            (a = r[n]),
              e.indexOf(a) >= 0 ||
                (Object.prototype.propertyIsEnumerable.call(t, a) &&
                  (b[a] = t[a]));
        }
        return b;
      }
      var p = b.a.createContext({}),
        O = function(t) {
          var e = b.a.useContext(p),
            a = e;
          return t && (a = 'function' == typeof t ? t(e) : i(i({}, e), t)), a;
        },
        o = function(t) {
          var e = O(t.components);
          return b.a.createElement(p.Provider, { value: e }, t.children);
        },
        d = {
          inlineCode: 'code',
          wrapper: function(t) {
            var e = t.children;
            return b.a.createElement(b.a.Fragment, {}, e);
          },
        },
        j = b.a.forwardRef(function(t, e) {
          var a = t.components,
            n = t.mdxType,
            r = t.originalType,
            l = t.parentName,
            p = c(t, ['components', 'mdxType', 'originalType', 'parentName']),
            o = O(a),
            j = n,
            u = o[''.concat(l, '.').concat(j)] || o[j] || d[j] || r;
          return a
            ? b.a.createElement(u, i(i({ ref: e }, p), {}, { components: a }))
            : b.a.createElement(u, i({ ref: e }, p));
        });
      function u(t, e) {
        var a = arguments,
          n = e && e.mdxType;
        if ('string' == typeof t || n) {
          var r = a.length,
            l = new Array(r);
          l[0] = j;
          var i = {};
          for (var c in e) hasOwnProperty.call(e, c) && (i[c] = e[c]);
          (i.originalType = t),
            (i.mdxType = 'string' == typeof t ? t : n),
            (l[1] = i);
          for (var p = 2; p < r; p++) l[p] = a[p];
          return b.a.createElement.apply(null, l);
        }
        return b.a.createElement.apply(null, a);
      }
      j.displayName = 'MDXCreateElement';
    },
  },
]);
