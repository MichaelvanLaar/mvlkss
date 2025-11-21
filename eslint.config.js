import js from '@eslint/js';
import globals from 'globals';
import compatPlugin from 'eslint-plugin-compat';

export default [
  // Global ignores
  {
    ignores: [
      'node_modules/**',
      'vendor/**',
      'kirby/**',
      'assets/**',
      '.phpunit.cache/**',
      '.coverage/**',
      'dist/**',
      'build/**',
    ],
  },

  // JavaScript files
  {
    files: ['src/js/**/*.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
        ...globals.es2021,
      },
    },
    plugins: {
      compat: compatPlugin,
    },
    rules: {
      ...js.configs.recommended.rules,

      // Best practices
      'no-console': ['warn', { allow: ['warn', 'error'] }],
      'no-debugger': 'warn',
      'no-alert': 'warn',
      'no-var': 'error',
      'prefer-const': 'error',
      'prefer-arrow-callback': 'warn',

      // Code style
      'quotes': ['error', 'single', { avoidEscape: true }],
      'semi': ['error', 'always'],
      'comma-dangle': ['error', 'always-multiline'],
      'indent': ['error', 2, { SwitchCase: 1 }],

      // Browser compatibility
      'compat/compat': 'warn',
    },
  },

  // Test files
  {
    files: ['tests/js/**/*.js', 'src/js/**/*.{test,spec}.js'],
    languageOptions: {
      globals: {
        ...globals.node,
        ...globals.browser,
        describe: 'readonly',
        it: 'readonly',
        test: 'readonly',
        expect: 'readonly',
        beforeEach: 'readonly',
        afterEach: 'readonly',
        beforeAll: 'readonly',
        afterAll: 'readonly',
        vi: 'readonly',
      },
    },
    rules: {
      'no-console': 'off',
    },
  },

  // Configuration files
  {
    files: ['*.config.js', '.eslintrc.js'],
    languageOptions: {
      globals: {
        ...globals.node,
      },
    },
  },
];
