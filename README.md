# Michael van Laar’s Kirby Site Starter

This is a work in progress. This documentation should reflect the current state of development, but may be incomplete.

If you need a pair programming buddy who knows the tech stack used in this project, you may want to ask the custom GPT [custom GPT “Web developer for MvLKSS based projects”](https://chat.openai.com/g/g-64y755npL-web-developer-for-mvlkss-based-projects) (ChatGPT Plus required).

## Prerequisites

The following must be installed on your machine:

- **Everything listed in the PHP section of the [Kirby CMS Requirements](https://getkirby.com/docs/guide/quickstart#requirements)**  
  Please note that for development purposes, you do not require any of the web servers listed in the requirements. Instead, utilize PHP’s built-in server when developing. However, one of the listed “real” webservers is essential when deploying the website.
- **On the production server: [ImageMagick](https://imagemagick.org/) in its latest version**  
  ImageMagick is a better choice for server-side image processing than the GD library. It is especially needed to create AVIF files. Therefore, in Kirby’s `config.php` ImageMagick is set up be used as thumbs driver – except in `localhost` environments (where GDLib is used because ImageMagick can cause problems on Windows machines).
  ImageMagick is the better choice for server-side image processing as opposed to GD Library. It is mainly necessary to generate AVIF files. Therefore it is set in the `config.php` of PHP that ImageMagick should be used - except in `localhost` environments (there GDLib is used, because otherwise there are problems on Windows machines).
- **On your development machine: [Composer](https://getcomposer.org/), a dependency manager for PHP**  
  Make sure all platform requirements are met by running the command `composer check-platform-reqs` after successfully installing Composer.
- **On your development machine: [Node.js](https://nodejs.org/) and [npm](https://www.npmjs.com/)**  
  All packages and dependencies in this repository are regularly updated to their latest versions.

## Install

### Step 1: Clone the Repository

Use the cloning method you like best.

### Step 2 (on Development Machines Only): Install Tailwind CSS and All Required Build Tools

```bash
npm install
```

This is only required on development machines. Once the build script has been run at least once, all essential assets are present and can be transferred to the production server. No npm or npm modules are necessary on the production server.

## Update

### General Rule

**Perform all updates and upgrades exclusively on a development machine and then commit them to the repository.** By doing so, deployment becomes more efficient since there is no need to execute any update, installation, or script commands on the production server.

For the sake of ease of deployment, the subdirectories `/kirby/*`, `/site/plugins/*`, and `/vendor/*` are committed to the repository despite the use of Composer.

### Update Scripts

The `package.json` includes two useful scripts that conveniently facilitate the process of checking and updating both Composer packages and npm packages.

#### Checking for Updates

The `utility-dependencies-update-check` script checks for new updates of your installed Composer and npm packages and displays the available information. It does not affect any of your files.

```bash
npm run utility-dependencies-update-check
```

#### Updating

The `utility-dependencies-update` script installs all available updates to your installed Composer and npm packages, based on the information in your `composer.json` and `package.json` files.

```bash
npm run utility-dependencies-update
```

## Use

### Development on a Local Machine

Open a terminal and start PHP’s built-in server and all necessary build tools for Tailwind CSS, bundling, and transpiling your frontend JavaScript in watch mode by using this command:

```bash
npm run dev-server
```

Open `http://localhost:8000` in your browser to browse the website.[^1]

### Remote Development Directly on a Web Space or Web Server

Since there is no need for PHP’s built-in server in this case, simply start all the build tools (for Tailwind CSS as well as for bundling and transpiling your frontend JavaScript) in watch mode using this command:

```bash
npm run dev
```

Remember, in order to develop remotely, you’ll need to have Node.js and npm on your web space or web server. However, it’s important to note that Node.js and npm are not required on your production web server.

Open the website in your browser as usual.[^1]

### Building Production-Ready CSS and JS Files

When development is complete, utilize the following command to generate streamlined and optimized CSS and JS files:

```bash
npm run build
```

### Cleaning Up Your Local Git Branches

This project features a Node.js script aimed at simplifying the task of deleting local Git branches. The script automatically eliminates branches that have been merged and deleted from the remote repository, effectively ensuring that the local development environment stays neat and tidy.

You can use this command to easily run the script:

```bash
npm run utility-git-branches-clean-up
```

Always make sure you're on the `main` branch (or any other branch that should not be removed) when running this script.

**Customization:** If your main branch has a different name than `main`, modify the file `.git-branches-clean-up.js` in the root directory accordingly by replacing the branch names in the exclusion condition.

## File Locations

### Kirby Files (e.g. Content and Templates)

See [Kirby’s comprehensive documentation](https://getkirby.com/docs/guide).

### CSS

The input file for Tailwind CSS can be found at `/src/css/main.css`.

After using the mentioned build tools, the output will be located in `/assets/css/main.css`. It is essential to link this CSS file to your Kirby templates.

**Please note:** The input CSS file needs to be imported into a JavaScript file so that Webpack can process it. This is accomplished with the file `/src/js/maincss.js`. Do not delete this file. Otherwise, the output CSS file based on the tailwind CSS classes in your templates will not be generated by Webpack.

### JavaScript

To keep things organized and simple, separate your frontend scripts into partials stored in the `/src/js/main-partials/` folder.

Then, import them into `/src/js/main.js`, which Webpack will use as the entry point to bundle everything (including imported Node.js modules) and create the output file `assets/js/main.js`. This is the file that needs to be linked in your Kirby templates.

## Color Scheme

Here's how to set up a brand color palette for the website and give editors access to specific brand colors (such as in background color selection fields):

1. Unless you exclusively utilize the default Tailwind CSS colors as your brand colors for website design, you should **incorporate your custom colors in the `tailwind.config.js` file.** For more information on adding supplementary colors to the Tailwind CSS setup, refer to the [Tailwind CSS documentation](https://tailwindcss.com/docs/customizing-colors#adding-additional-colors).
2. **Specify the colors you wish to offer for selection by website editors in Kirby’s `/site/config/config.php` file,** utilizing the relevant Tailwind CSS utility classes.
3. Note that the idea is to define various Tailwind CSS utility classes for each color option that can be selected to accommodate both light and dark modes while also defining corresponding contrast colors. These contrast colors are employed, for instance, to guarantee that any text shown over the designated brand color background remains legible at all times.  
   **Define all Tailwind CSS utility classes you wish to use for a brand color separately,** as each utility class must be present as a complete string in a PHP file to ensure accurate identification during the build process. Refer to the comment within the appropriate section of the `/site/config/config.php` file for specific examples and further details.
4. To add unique contrast colors beyond black and white, simply **include additional color schemes for the Tailwind CSS typography plugin.** To do so, copy the `prose-black` and `prose-white` utility class examples in `tailwind.config.js` and use them to add your own typography colors. For further details, refer to the [“Adding custom color themes” section](https://tailwindcss.com/docs/typography-plugin#adding-custom-color-themes) within the Tailwind CSS typography plugin documentation.

## Tools Included

- [Kirby](https://getkirby.com/)
- Kirby plugins:
  - [Clear Cache](https://owebstudio.com/kirby/plugins/clear-cache)
  - [Date Extended](https://github.com/Adspectus/k3-date-extended)
  - [Grid Block](https://github.com/youngcut/kirby-grid-block)
  - [Hashed Assets](https://github.com/johannschopplich/kirby-hashed-assets)
  - [Kirby Highlighter](https://github.com/johannschopplich/kirby-highlighter)
  - [Minify HTML](https://github.com/afbora/kirby-minify-html)
  - [Retour](https://github.com/distantnative/retour-for-kirby)
  - [Robots.txt](https://github.com/bnomei/kirby3-robots-txt)
  - [Snippet Controller](https://github.com/lukaskleinschmidt/kirby-snippet-controller)
- [PostCSS](https://postcss.org/)
- PostCSS plugins:
  - [Tailwind CSS](https://tailwindcss.com/) (including the [forms plugin](https://tailwindcss.com/docs/plugins#forms), the [aspect ration plugin](https://tailwindcss.com/docs/plugins#aspect-ratio) and the [typography plugin](https://tailwindcss.com/docs/plugins#typography))
  - [Autoprefixer](https://github.com/postcss/autoprefixer)
  - [cssnano](https://cssnano.co/)
- [Babel](https://babeljs.io/)
- [webpack](https://webpack.js.org/)
- webpack plugins:
  - [copy-webpack-plugin](https://github.com/webpack-contrib/copy-webpack-plugin)
  - [mini-css-extract-plugin](https://github.com/webpack-contrib/mini-css-extract-plugin)
- Composer plugins:
  - [composer-normalize](https://github.com/ergebnis/composer-normalize)
- [Playwright](https://playwright.dev/)
- [Axios](https://axios-http.com/)
- [node-xml2js](https://github.com/Leonidas-from-XIV/node-xml2js)

## Best Practices / Tutorials Included

- [Accessible hamburger buttons without JavaScript](https://www.pausly.app/blog/accessible-hamburger-buttons-without-javascript)
- [Responsive images](https://getkirby.com/docs/cookbook/performance/responsive-images) (in image blocks)
- [Image modal (i. e. lightbox)](https://www.kindacode.com/article/tailwind-css-how-to-create-image-modals-image-lightboxes/)

[^1]: No browser sync or auto-refresh feature is included. Just use a browser extension like [Tab Auto Refresh](https://addons.mozilla.org/de/firefox/addon/tab-auto-refresh/) for Firefox. Or use a development browser like [Polypane](https://polypane.app/) which has a handy live reload feature already built in.
