# Using SimpleSAML - Notes
## Getting Started

In the directory `simplesaml` make sure there the following directories exist

```
cache
certs
data
log
tmp
```

You will need to create certificates for using simpleSAML as an IDP: In the direcory `simplesaml/certs`
execute the following to create the certificate files:

```
openssl req -newkey rsa:3072 -new -x509 -days 3652 -nodes -out idp_simplesaml.crt -keyout idp_simplesaml.pem
```

And

```
openssl req -newkey rsa:3072 -new -x509 -days 3652 -nodes -out sp_simplesaml.crt -keyout sp_simplesaml.pem
```

I think setting up the SP certificates may not be needed when Drupal is the IDP.

## NGINX note

I added the following location to NGINX for the simpleSAML admin

```
    location ^~ /simplesaml {
        # Map URL to SimpleSAMLphp public directory
        alias /var/www/html/vendor/simplesamlphp/simplesamlphp/public;
        index index.php index.html index.htm default.php default.htm default.html;
        
        # Access control - equivalent to "Require all granted"
        allow all;
        
        location ~ \.php($|/) {
          # NOTE: this may need to change for non-ddev installations ....
          fastcgi_param SIMPLESAMLPHP_CONFIG_DIR /var/www/html/simplesaml/dev/config;

          fastcgi_split_path_info ^(.+\.php)(/.+)$;
          fastcgi_pass unix:/run/php/php-fpm.sock;
          fastcgi_index index.php;
          include fastcgi_params;

          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

          fastcgi_param DOCUMENT_ROOT    $realpath_root;
          fastcgi_param SCRIPT_FILENAME  $request_filename;

          fastcgi_param PATH_INFO       $fastcgi_path_info;
        }
    }
```

Note that the fast cgi parameter for the config direcory also needed to be set in
the location for the drupal fast cgi config.

```
fastcgi_param SIMPLESAMLPHP_CONFIG_DIR /var/www/html/simplesaml/dev/config;
```

## Notes

I have set up environment variables that are created on ddev start that the simple
SAML configs use

```
SAML_SP_PRIMARY_HOST
SAML_IDP_PRIMARY_HOST
SSP_SECRET_SALT
```

This means you do not need to do anything to the SAML config files.

## Redis

I am using Redis for the SAML session store. Ddev has been configured with
a redis instance for this.

## Sanity check

Make sure that module drupalauth4spp is enabled. The default settings are fine for now.

To test go to `https://dublin2029.ddev.site/simplesaml/` and you should see the simple
SAML welcom page.

Then go tp `https://dublin2029.ddev.site/simplesaml/admin` and you can login with as the
SAML admin (default password is just `admin`). From there you can make sure things are configured
properly and test the authentication providers.

The  `default-sp` is using `drupal-userpass` and will take you to the drupal login page.

## User Attributes (TODO)

In the file `simplesaml/dev/config/authsources.php` we can modify the attributes that are
passed along on authentication. We should tweak this to match what we need (re membership. roles etc)

