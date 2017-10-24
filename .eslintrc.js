module.exports = {
  // Use Babel parser when running linter so
  // we can use newer language features.
  parser: 'babel-eslint',

  // Extend our DoSomething.org code style.
  // https://github.com/dosomething/eslint-config
  extends: '@dosomething/eslint-config',

  env: {
    // Register Jest global variables.
    jest: true,
  },

  rules: {
    // For now, only warn on missing alt or caption.
    'jsx-a11y/alt-text': 'warn',
    'jsx-a11y/media-has-caption': 'warn',

    // Require multi-line curly braces for all conditionals.
    'curly': ['error', 'all'],
    'brace-style': ['error', '1tbs', { allowSingleLine: false }],
  }
};
