/* eslint-env es6 */
'use strict';

/**
 * To start theme building process, define the theme name below,
 * then run "gulp" in command line.
 */

 // External dependencies
 import {src, dest, watch as gulpWatch, parallel, series} from 'gulp';
 import autoprefixer from 'autoprefixer';
 import browserSync from 'browser-sync';
 import colors from 'ansi-colors';
 import log from 'fancy-log';
 import partialImport from 'postcss-partial-import';
 import postcssPresetEnv from 'postcss-preset-env';
 import pump from 'pump';
 import requireUncached from 'require-uncached';

// Internal dependencies
import {rootPath, paths, gulpPlugins, gulpReplaceOptions} from './gulp/constants';
import styles from './gulp/styles';
import {serve, reload} from './gulp/browserSync';
import php from './gulp/php';
import sassStyles from './gulp/sassStyles';
import scripts from './gulp/scripts';

// Get a fresh copy of the config
let config = requireUncached(`${rootPath}/dev/config/themeConfig.js`);


/**
 * Copy JS libraries without touching them.
 */
export function jsLibs(done) {
	pump([
        src(paths.scripts.libs),
        gulpPlugins.newer(paths.scripts.verboseLibsDest),
        dest(paths.scripts.verboseLibsDest),
        dest(paths.scripts.libsDest),
    ], done);
}


/**
 * Copy minified JS files without touching them.
 */
export function jsMin(done) {
	pump([
        src(paths.scripts.min),
        gulpPlugins.newer(paths.scripts.dest),
        dest(paths.verbose),
        dest(paths.scripts.dest),
    ], done);
}

/**
 * Optimize images.
 */
export function images(done) {
	pump([
        src(paths.images.src),
        gulpPlugins.newer(paths.images.dest),
        gulpPlugins.image(),
        dest(paths.images.dest),
    ], done);
}


/**
 * Watch everything
 */
export function watch() {
	gulpWatch(paths.php.src, series(php, reload));
	gulpWatch(paths.config.themeConfig, series(php, reload));
	gulpWatch(paths.config.cssVars, series(styles, reload));
	gulpWatch(paths.styles.sass, sassStyles);
	gulpWatch(paths.styles.src, series(styles, reload));
	gulpWatch(paths.scripts.src, series(scripts, reload));
	gulpWatch(paths.scripts.min, series(jsMin, reload));
	gulpWatch(paths.scripts.libs, series(jsLibs, reload));
	gulpWatch(paths.images.src, series(images, reload));
}


/**
 * Map out the sequence of events on first load:
 */
const firstRun = series(php, parallel(scripts, jsMin, jsLibs), sassStyles, styles, images, serve, watch);


/**
 * Run the whole thing.
 */
export default firstRun;


/**
 * Generate translation files.
 */
export function translate(done) {
	pump([
        src(paths.languages.src),
        gulpPlugins.sort(),
        gulpPlugins.wpPot({
            domain: config.theme.name,
            package: config.theme.name,
            bugReport: config.theme.name,
            lastTranslator: config.theme.author
        }),
        dest(paths.languages.dest),
    ], done);
}


/**
 * Create zip archive from generated theme files.
 */
export function bundle(done) {
	pump([
        src(paths.export.src),
        // gulpPlugins.print(),
        gulpPlugins.if(
            config.export.compress, 
            gulpPlugins.zip(`${config.theme.name}.zip`), 
            dest(`${paths.export.dest}${config.theme.name}`)
        ),
        gulpPlugins.if(
            config.export.compress, 
            dest(paths.export.dest)
        ),
    ], done);
}


/**
 * Test the theme.
 */
const testTheme = series(php);


/**
 * Export theme for distribution.
 */
const bundleTheme = series(testTheme, parallel(scripts, jsMin, jsLibs), styles, images, translate, bundle);

export { testTheme, bundleTheme, scripts, styles };
