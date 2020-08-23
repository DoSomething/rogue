(window.webpackJsonp = window.webpackJsonp || []).push([
  [12],
  {
    66: function(e, t, r) {
      'use strict';
      r.r(t),
        r.d(t, 'frontMatter', function() {
          return o;
        }),
        r.d(t, 'metadata', function() {
          return c;
        }),
        r.d(t, 'rightToc', function() {
          return l;
        }),
        r.d(t, 'default', function() {
          return u;
        });
      var n = r(2),
        a = r(6),
        i = (r(0), r(88)),
        o = { title: 'Overview', sidebar_label: 'Overview' },
        c = {
          unversionedId: 'development/overview',
          id: 'development/overview',
          isDocsHomePage: !1,
          title: 'Overview',
          description: 'Architecture',
          source: '@site/docs/development/overview.md',
          permalink: '/build/docs/development/overview',
          editUrl:
            'https://github.com/facebook/docusaurus/edit/master/website/docs/development/overview.md',
          sidebar_label: 'Overview',
          sidebar: 'sidebar',
          next: {
            title: 'Contributing',
            permalink: '/build/docs/development/contributing',
          },
        },
        l = [
          { value: 'Architecture', id: 'architecture', children: [] },
          { value: 'Getting Started', id: 'getting-started', children: [] },
          {
            value: 'Security Vulnerabilities',
            id: 'security-vulnerabilities',
            children: [],
          },
          { value: 'License', id: 'license', children: [] },
        ],
        s = { rightToc: l };
      function u(e) {
        var t = e.components,
          r = Object(a.a)(e, ['components']);
        return Object(i.b)(
          'wrapper',
          Object(n.a)({}, s, r, { components: t, mdxType: 'MDXLayout' }),
          Object(i.b)('h2', { id: 'architecture' }, 'Architecture'),
          Object(i.b)(
            'p',
            null,
            'This is ',
            Object(i.b)('strong', { parentName: 'p' }, 'Rogue'),
            ', the DoSomething.org user activity service. Rogue is built using ',
            Object(i.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                { href: 'https://laravel.com/docs/6.x' },
              ),
              'Laravel 6',
            ),
            ' for the backend on top of a MySQL (Maria DB) database and with a ',
            Object(i.b)(
              'a',
              Object(n.a)({ parentName: 'p' }, { href: 'http://reactjs.com' }),
              'React',
            ),
            ' frontend.',
          ),
          Object(i.b)('h2', { id: 'getting-started' }, 'Getting Started'),
          Object(i.b)(
            'p',
            null,
            'To get started with development, check out our communal docs for installing ',
            Object(i.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                {
                  href:
                    'https://github.com/DoSomething/communal-docs/tree/master/Homestead',
                },
              ),
              'Homestead',
            ),
            '.',
          ),
          Object(i.b)(
            'p',
            null,
            'After installation, run ',
            Object(i.b)(
              'inlineCode',
              { parentName: 'p' },
              'php artisan db:seed',
            ),
            ' to create sample campaign and activity data.',
          ),
          Object(i.b)(
            'p',
            null,
            'You can additionally seed your database with sample voter registration data by running:',
          ),
          Object(i.b)(
            'pre',
            null,
            Object(i.b)(
              'code',
              Object(n.a)({ parentName: 'pre' }, { className: 'language-php' }),
              'php artisan db:seed --class=VoterRegistrationSeeder\n',
            ),
          ),
          Object(i.b)(
            'h2',
            { id: 'security-vulnerabilities' },
            'Security Vulnerabilities',
          ),
          Object(i.b)(
            'p',
            null,
            'We take security very seriously. Any vulnerabilities in Rogue should be reported to ',
            Object(i.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                { href: 'mailto:security@dosomething.org' },
              ),
              'security@dosomething.org',
            ),
            ', and will be promptly addressed. Thank you for taking the time to responsibly disclose any issues you find.',
          ),
          Object(i.b)('h2', { id: 'license' }, 'License'),
          Object(i.b)(
            'p',
            null,
            '\xa9',
            '2020 DoSomething.org. Rogue is free software, and may be redistributed under the terms specified in the ',
            Object(i.b)(
              'a',
              Object(n.a)(
                { parentName: 'p' },
                {
                  href:
                    'https://github.com/DoSomething/rogue/blob/master/LICENSE',
                },
              ),
              'LICENSE',
            ),
            ' file. The name and logo for DoSomething.org are trademarks of Do Something, Inc and may not be used without permission.',
          ),
        );
      }
      u.isMDXComponent = !0;
    },
    88: function(e, t, r) {
      'use strict';
      r.d(t, 'a', function() {
        return d;
      }),
        r.d(t, 'b', function() {
          return m;
        });
      var n = r(0),
        a = r.n(n);
      function i(e, t, r) {
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
      function o(e, t) {
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
      function c(e) {
        for (var t = 1; t < arguments.length; t++) {
          var r = null != arguments[t] ? arguments[t] : {};
          t % 2
            ? o(Object(r), !0).forEach(function(t) {
                i(e, t, r[t]);
              })
            : Object.getOwnPropertyDescriptors
            ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(r))
            : o(Object(r)).forEach(function(t) {
                Object.defineProperty(
                  e,
                  t,
                  Object.getOwnPropertyDescriptor(r, t),
                );
              });
        }
        return e;
      }
      function l(e, t) {
        if (null == e) return {};
        var r,
          n,
          a = (function(e, t) {
            if (null == e) return {};
            var r,
              n,
              a = {},
              i = Object.keys(e);
            for (n = 0; n < i.length; n++)
              (r = i[n]), t.indexOf(r) >= 0 || (a[r] = e[r]);
            return a;
          })(e, t);
        if (Object.getOwnPropertySymbols) {
          var i = Object.getOwnPropertySymbols(e);
          for (n = 0; n < i.length; n++)
            (r = i[n]),
              t.indexOf(r) >= 0 ||
                (Object.prototype.propertyIsEnumerable.call(e, r) &&
                  (a[r] = e[r]));
        }
        return a;
      }
      var s = a.a.createContext({}),
        u = function(e) {
          var t = a.a.useContext(s),
            r = t;
          return e && (r = 'function' == typeof e ? e(t) : c(c({}, t), e)), r;
        },
        d = function(e) {
          var t = u(e.components);
          return a.a.createElement(s.Provider, { value: t }, e.children);
        },
        p = {
          inlineCode: 'code',
          wrapper: function(e) {
            var t = e.children;
            return a.a.createElement(a.a.Fragment, {}, t);
          },
        },
        b = a.a.forwardRef(function(e, t) {
          var r = e.components,
            n = e.mdxType,
            i = e.originalType,
            o = e.parentName,
            s = l(e, ['components', 'mdxType', 'originalType', 'parentName']),
            d = u(r),
            b = n,
            m = d[''.concat(o, '.').concat(b)] || d[b] || p[b] || i;
          return r
            ? a.a.createElement(m, c(c({ ref: t }, s), {}, { components: r }))
            : a.a.createElement(m, c({ ref: t }, s));
        });
      function m(e, t) {
        var r = arguments,
          n = t && t.mdxType;
        if ('string' == typeof e || n) {
          var i = r.length,
            o = new Array(i);
          o[0] = b;
          var c = {};
          for (var l in t) hasOwnProperty.call(t, l) && (c[l] = t[l]);
          (c.originalType = e),
            (c.mdxType = 'string' == typeof e ? e : n),
            (o[1] = c);
          for (var s = 2; s < i; s++) o[s] = r[s];
          return a.a.createElement.apply(null, o);
        }
        return a.a.createElement.apply(null, r);
      }
      b.displayName = 'MDXCreateElement';
    },
  },
]);
