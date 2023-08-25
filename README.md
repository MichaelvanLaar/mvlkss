# Michael van Laar’s Kirby Site Starter

This is work in progress. This documentation should reflect the current state of development, but may be incomplete.

## Prerequisites

The following must be installed on your machine:

- **Everything listed in the PHP section of the [Kirby CMS Requirements](https://getkirby.com/docs/guide/quickstart#requirements)**  
  Please note that for development purposes, you do not need any of the web servers listed in the requirements. Instead, you will use PHP’s built-in server while developing. Of course, you need one of the listed “real” webservers when you want to deploy the website.
- **[Composer](https://getcomposer.org/), a dependency manager for PHP**  
  Make sure that all platform requirements are satisfied. You can do this by running the following command after composer is successfully installed: `composer check-platform-reqs`
- **[Node.js](https://nodejs.org/)**

## Install

### Step 1: Clone the Repository

Use whichever clone method you like best.

### Step 2: Install Kirby

```bash
composer install
```

### Step 3: Install Tailwind CSS and All Required Build Tools

```bash
npm install
```

## Use

### Development on a Local Machine

Open a terminal and start PHP’s built-in server as well as all build tools (for Tailwind CSS as well as for bundling and transpiling your frontend JavaScript) in watch mode:

```bash
npm run dev-server
```

Open `http://localhost:8000` in your browser to browse the website.[^1]

### Remote Development Directly on a Web Space or Web Server

Since in this cas you don’t need PHP’s built-in server, it is enough to start all build tools (for Tailwind CSS as well as for bundling and transpiling your frontend JavaScript) in watch mode:

```bash
npm run dev
```

Open the website in your browser as usual.[^1]

### Building Production-Ready CSS and JS Files

When you’re done developing, you can use the following command to create minified and optimized versions of your CSS and JS files:

```bash
npm run build
```

## File Locations

### Kirby Files (e.g. Content and Templates)

See [Kirby’s comprehensive documentation](https://getkirby.com/docs/guide).

### CSS

The input file for Tailwind CSS is `/src/css/main.css`.

The output is rendered to `/assets/css/main.css` using the build tools mentioned above. This is the CSS file that needs to be linked in your Kirby templates.

**Please note:** The input CSS file must be imported into a JavaScript file for Webpack to be able to process it. This is done via the file `/src/js/maincss.js`. Do not delete this file, otherwise Webpack will not be able to create the output CSS file based on the tailwind CSS classes used in your templates.

### JavaScript

To keep everything neat and simple, organize your frontend scripts in separate partials (stored in the folder `/src/js/main-partials/`) and import them into `/src/js/main.js`.

Starting with `/src/js/main.js` as entry point, Webpack will bundle everything (including imported node modules) and create the output file `assets/js/main.js`. This is the JS file that needs to be linked in your Kirby templates.

## Tools Included

- [Kirby](https://getkirby.com/) (via [Composer](https://getcomposer.org/))
- Kirby plugins (via [Composer](https://getcomposer.org/)):
  - [Bettersearch](https://github.com/bvdputte/kirby-bettersearch)
  - [Color Extractor](https://github.com/sylvainjule/kirby-colorextractor)
  - [Colors](https://github.com/hananils/kirby-colors)
  - [Date Methods](https://github.com/hananils/kirby-date-methods)
  - [Grid Block](https://github.com/youngcut/kirby-grid-block)
  - [Hashed Assets](https://github.com/johannschopplich/kirby-hashed-assets)
  - [k3-date-extended](https://github.com/Adspectus/k3-date-extended)
  - [Kirby Highlighter](https://github.com/johannschopplich/kirby-highlighter)
  - [Kirby Inherit](https://github.com/youngcut/kirby-inherit)
  - [Maintenance Mode](https://github.com/moritzebeling/kirby-maintenance)
  - [Markdown Field](https://github.com/fabianmichael/kirby-markdown-field)
  - [Minify HTML](https://github.com/afbora/kirby-minify-html)
  - [Multilanguage XML Sitemap](https://github.com/isaactopo/xmlsitemap)
  - [Resolve](https://github.com/lukaskleinschmidt/kirby-resolve)
  - [Retour](https://github.com/distantnative/retour-for-kirby)
  - [Robots.txt](https://github.com/bnomei/kirby3-robots-txt)
  - [Snippet Controller](https://github.com/lukaskleinschmidt/kirby-snippet-controller)
- [PostCSS](https://postcss.org/)
- [Tailwind CSS](https://tailwindcss.com/) (including the [forms plugin](https://tailwindcss.com/docs/plugins#forms), the [aspect ration plugin](https://tailwindcss.com/docs/plugins#aspect-ratio) and the [typography plugin](https://tailwindcss.com/docs/plugins#typography))
- [Autoprefixer](https://github.com/postcss/autoprefixer)
- [cssnano](https://cssnano.co/)
- [Babel](https://babeljs.io/)
- [webpack](https://webpack.js.org/)

## Best Practices / Tutorials Included

- [Accessible hamburger buttons without JavaScript](https://www.pausly.app/blog/accessible-hamburger-buttons-without-javascript)
- [Responsive images](https://getkirby.com/docs/cookbook/performance/responsive-images) (in image blocks)

## To Do List

- [ ] Add button block.
- [ ] Add language switch which should only be displayed if more than one language is set up.
- [ ] Add hreflang links which should only be included if more than one language is set up.

[^1]: No browser sync or auto-refresh feature is included. Just use a browser extension like [Tab Auto Refresh](https://addons.mozilla.org/de/firefox/addon/tab-auto-refresh/) for Firefox. Or use a development browser like [Polypane](https://polypane.app/) which has a handy live reload feature already built in.
