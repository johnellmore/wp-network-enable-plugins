# wp-network-enable-plugins
WordPress plugin. Filters specific option calls (like `get_option()`) to make them use network options instead of site-specific options, thus allowing plugins to work site-wide with shared settings.

## Usage

Put something like the following in wp-config.php:

```define('NETWORK_ENABLE_OPTIONS', 'twp,twp_version,twp-authed-users');```

`NETWORK_ENABLE_OPTIONS` is a comma separated list of option names that should be redirected to use the multisite options table instead. You can probably find these options by searching through the source code of the plugin that you'd like to redirect.

In the example above, [Twitter Widget Pro](https://wordpress.org/plugins/twitter-widget-pro/) will now pull all of its settings from the network-wide options table. This means that multiple "subsites" can all be authenticated to use a specific Twitter account without having to explicitly authorize each one individually.

But **watch out**--since this plugin redirects function calls on a low-level, there are bound to be plugins that don't work with it correctly. Any plugin that makes it's own DB table, or adds permalinks, or other extra features may cause problems or behave unexpectedly. Make sure you test extra-carefully before deploying to production.
