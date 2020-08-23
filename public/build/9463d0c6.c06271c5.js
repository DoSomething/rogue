(window.webpackJsonp = window.webpackJsonp || []).push([
  [21],
  {
    75: function(e, t, r) {
      'use strict';
      r.r(t),
        r.d(t, 'frontMatter', function() {
          return i;
        }),
        r.d(t, 'metadata', function() {
          return l;
        }),
        r.d(t, 'rightToc', function() {
          return c;
        }),
        r.d(t, 'default', function() {
          return b;
        });
      var n = r(2),
        o = r(6),
        a = (r(0), r(88)),
        i = { title: 'Contributing', sidebar_label: 'Contributing' },
        l = {
          unversionedId: 'development/contributing',
          id: 'development/contributing',
          isDocsHomePage: !1,
          title: 'Contributing',
          description:
            'This section will go over some general points about contributing to Rogue as a developer. If you have any specific questions that are not addressed here, please reach out to Team Bleed.',
          source: '@site/docs/development/contributing.md',
          permalink: '/build/docs/development/contributing',
          editUrl:
            'https://github.com/facebook/docusaurus/edit/master/website/docs/development/contributing.md',
          sidebar_label: 'Contributing',
          sidebar: 'sidebar',
          previous: {
            title: 'Overview',
            permalink: '/build/docs/development/overview',
          },
          next: {
            title: 'API Overview',
            permalink: '/build/docs/api/overview',
          },
        },
        c = [
          { value: 'Pivotal Tracker', id: 'pivotal-tracker', children: [] },
          {
            value: 'GitHub',
            id: 'github',
            children: [
              { value: 'Workflow', id: 'workflow', children: [] },
              { value: 'Guidelines', id: 'guidelines', children: [] },
            ],
          },
        ],
        u = { rightToc: c };
      function b(e) {
        var t = e.components,
          r = Object(o.a)(e, ['components']);
        return Object(a.b)(
          'wrapper',
          Object(n.a)({}, u, r, { components: t, mdxType: 'MDXLayout' }),
          Object(a.b)(
            'p',
            null,
            'This section will go over some general points about contributing to Rogue as a developer. If you have any specific questions that are not addressed here, please reach out to Team Bleed.',
          ),
          Object(a.b)('h2', { id: 'pivotal-tracker' }, 'Pivotal Tracker'),
          Object(a.b)(
            'p',
            null,
            'We use ',
            Object(a.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                { href: 'https://www.pivotaltracker.com/n/projects/2019429' },
              ),
              'Pivotal Tracker',
            ),
            ' as our project management tool for tracking all of our teams work sprint-by-sprint.',
          ),
          Object(a.b)(
            'p',
            null,
            'Rogue maintenance and features are maintained primarily by Team Bleed. Please reach out to the product\nteam if you are having issues accessing the Team Bleed Pivotal board.',
          ),
          Object(a.b)('h2', { id: 'github' }, 'GitHub'),
          Object(a.b)(
            'p',
            null,
            "We use GitHub for our code managment platform. All of our code at DoSomething.org is open source and available to the public on GitHub. To start contrubuting, you only need to create a GitHub account (if you don't already have one) and visit the ",
            Object(a.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                { href: 'https://github.com/DoSomething/rogue' },
              ),
              'Rogue Repository',
            ),
            ' which includes installation instructions for local development.',
          ),
          Object(a.b)('h3', { id: 'workflow' }, 'Workflow'),
          Object(a.b)(
            'p',
            null,
            'When contributing to Rogue, follow these steps:',
          ),
          Object(a.b)(
            'ul',
            null,
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Pull down the most recent code on the ',
              Object(a.b)('inlineCode', { parentName: 'li' }, 'master'),
              ' branch.',
            ),
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Create a branch off of your local master branch and give it a name.',
            ),
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Commit all of your code changes to this new branch.',
            ),
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Rebase your feature branch with ',
              Object(a.b)('inlineCode', { parentName: 'li' }, 'master'),
              ' and push your branch up to the Rogue Repository on GitHub.',
            ),
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Create a ',
              Object(a.b)(
                'a',
                Object(n.a)(
                  { parentName: 'li' },
                  {
                    href:
                      'https://help.github.com/articles/about-pull-requests/',
                  },
                ),
                'Pull Request',
              ),
              ' that compares your branch to ',
              Object(a.b)('inlineCode', { parentName: 'li' }, 'master'),
              ' and add ',
              Object(a.b)(
                'inlineCode',
                { parentName: 'li' },
                'DoSomething/team-bleed',
              ),
              ' as a reviewer.',
            ),
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Members of #Team-Bleed will then review your proposed changes in a timely manner. There might be questions, comments, or suggestions that need to be addressed before final approval is recieved.',
            ),
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Once everything looks OK your PR will be officially approved and if all the CLI integrations have passed you will be free to merge your code.',
            ),
            Object(a.b)(
              'li',
              { parentName: 'ul' },
              'Click the green ',
              Object(a.b)('inlineCode', { parentName: 'li' }, 'Merge'),
              ' button and your code will be merged into master. See the ',
              Object(a.b)('strong', { parentName: 'li' }, 'Deployments'),
              ' for information about our deployment process.',
            ),
          ),
          Object(a.b)(
            'p',
            null,
            'For local development instuctions ',
            Object(a.b)('strong', { parentName: 'p' }, 'see here'),
            '.',
          ),
          Object(a.b)('h3', { id: 'guidelines' }, 'Guidelines'),
          Object(a.b)(
            'p',
            null,
            'We follow ',
            Object(a.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                {
                  href:
                    'http://laravel.com/docs/5.5/contributions#coding-style',
                },
              ),
              "Laravel's code style",
            ),
            ' and automatically\nlint all pull requests with ',
            Object(a.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                { href: 'https://styleci.io/repos/64166359' },
              ),
              'StyleCI',
            ),
            '. Be sure to configure\n',
            Object(a.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                { href: 'http://editorconfig.org' },
              ),
              'EditorConfig',
            ),
            ' to ensure you have proper indentation settings.',
          ),
        );
      }
      b.isMDXComponent = !0;
    },
    88: function(e, t, r) {
      'use strict';
      r.d(t, 'a', function() {
        return s;
      }),
        r.d(t, 'b', function() {
          return m;
        });
      var n = r(0),
        o = r.n(n);
      function a(e, t, r) {
        return (
          t in e
            ? Object.defineProperty(e, t, {
                value: r,
                enumerable: !0,
                configurable: !0,
                writable: !0,
              })
            : (e[t] = r),
          e
        );
      }
      function i(e, t) {
        var r = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
          var n = Object.getOwnPropertySymbols(e);
          t &&
            (n = n.filter(function(t) {
              return Object.getOwnPropertyDescriptor(e, t).enumerable;
            })),
            r.push.apply(r, n);
        }
        return r;
      }
      function l(e) {
        for (var t = 1; t < arguments.length; t++) {
          var r = null != arguments[t] ? arguments[t] : {};
          t % 2
            ? i(Object(r), !0).forEach(function(t) {
                a(e, t, r[t]);
              })
            : Object.getOwnPropertyDescriptors
            ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(r))
            : i(Object(r)).forEach(function(t) {
                Object.defineProperty(
                  e,
                  t,
                  Object.getOwnPropertyDescriptor(r, t),
                );
              });
        }
        return e;
      }
      function c(e, t) {
        if (null == e) return {};
        var r,
          n,
          o = (function(e, t) {
            if (null == e) return {};
            var r,
              n,
              o = {},
              a = Object.keys(e);
            for (n = 0; n < a.length; n++)
              (r = a[n]), t.indexOf(r) >= 0 || (o[r] = e[r]);
            return o;
          })(e, t);
        if (Object.getOwnPropertySymbols) {
          var a = Object.getOwnPropertySymbols(e);
          for (n = 0; n < a.length; n++)
            (r = a[n]),
              t.indexOf(r) >= 0 ||
                (Object.prototype.propertyIsEnumerable.call(e, r) &&
                  (o[r] = e[r]));
        }
        return o;
      }
      var u = o.a.createContext({}),
        b = function(e) {
          var t = o.a.useContext(u),
            r = t;
          return e && (r = 'function' == typeof e ? e(t) : l(l({}, t), e)), r;
        },
        s = function(e) {
          var t = b(e.components);
          return o.a.createElement(u.Provider, { value: t }, e.children);
        },
        p = {
          inlineCode: 'code',
          wrapper: function(e) {
            var t = e.children;
            return o.a.createElement(o.a.Fragment, {}, t);
          },
        },
        d = o.a.forwardRef(function(e, t) {
          var r = e.components,
            n = e.mdxType,
            a = e.originalType,
            i = e.parentName,
            u = c(e, ['components', 'mdxType', 'originalType', 'parentName']),
            s = b(r),
            d = n,
            m = s[''.concat(i, '.').concat(d)] || s[d] || p[d] || a;
          return r
            ? o.a.createElement(m, l(l({ ref: t }, u), {}, { components: r }))
            : o.a.createElement(m, l({ ref: t }, u));
        });
      function m(e, t) {
        var r = arguments,
          n = t && t.mdxType;
        if ('string' == typeof e || n) {
          var a = r.length,
            i = new Array(a);
          i[0] = d;
          var l = {};
          for (var c in t) hasOwnProperty.call(t, c) && (l[c] = t[c]);
          (l.originalType = e),
            (l.mdxType = 'string' == typeof e ? e : n),
            (i[1] = l);
          for (var u = 2; u < a; u++) i[u] = r[u];
          return o.a.createElement.apply(null, i);
        }
        return o.a.createElement.apply(null, r);
      }
      d.displayName = 'MDXCreateElement';
    },
  },
]);
