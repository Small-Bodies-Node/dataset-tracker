# What's This?

This dir is for configuration files to encrypt database connections from php. To set it up, you need to add two files to this dir:

## `config.user.inc.php`

```php
<?php
$cfg['Servers'][1]['host'] = 'change-to-aws-rds-host';
$cfg['Servers'][1]['ssl_ca'] = '/etc/phpmyadmin/rds-combined-ca-bundle.pem';
$cfg['Servers'][1]['ssl_verify'] = true;
$cfg['Servers'][1]['ssl'] = true;
```

## `rds-combined-ca-bundle.pem`

Download this from [here](https://s3.amazonaws.com/rds-downloads/rds-combined-ca-bundle.pem).
