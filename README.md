# Extension:OAuth

For a full description see [Extension:OAuth on mediawiki.org](https://www.mediawiki.org/wiki/Extension:OAuth).

## Experimental REST API

To enable the `/oauth/clients` REST API you must set

    $wgRestAPIAdditionalRouteFiles[] = "$wgExtensionDirectory/OAuth/experimentalRoutes.json";

in your `LocalSettings.php`