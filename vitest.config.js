import { defineConfig } from 'vitest/config';
import path from 'path';

export default defineConfig({
  test: {
    globals: true,
    environment: 'jsdom',
    setupFiles: ['./tests/js/setup.js'],
    coverage: {
      provider: 'v8',
      reporter: ['text', 'html', 'lcov'],
      reportsDirectory: '.coverage/js',
      include: ['src/js/**/*.js'],
      exclude: [
        'src/js/**/*.test.js',
        'src/js/**/*.spec.js',
        'node_modules/**',
        'tests/**',
      ],
    },
    include: ['tests/js/**/*.{test,spec}.js', 'src/js/**/*.{test,spec}.js'],
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
      '@js': path.resolve(__dirname, './src/js'),
    },
  },
});
