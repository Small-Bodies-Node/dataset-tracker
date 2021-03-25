# What's This?

Repo to explore migration of DatasetTracker from php5.6 to php7.2

## Approach Overview

- Use docker container to get 'legacy' code working locally with legacy version of mysql and php

- Use docker container to get update 'target' code working locally with target version of mysql and php

## Quickstart

For local development:

- Copy `.env-template` to `.env`

- Install docker locally, then run bash script `./_docker_manager` for options

- All local code is viewable on the $PORT defined in `_docker_manager`, 3050 at the moment. When docker is running, go to `localhost:3050` in your browser

- Basic auth credentials in development: admin - password

## Deployment

[TODO]

## Dev Notes

Here are a bunch of notes about various issues encountered when doing migration/upgrade:

- Old code used `@` before certain variables to suppress errors/warnings. As expressed [here](https://stackoverflow.com/a/4151431/8620332) this is "pure evil"
- Old deployment did not use SSL, we need this to securely transmit credentials
- Deprecated `mysql_pconnect`
- Password to DB hardcoded in; needs to be separated out into .env file
- Old code used the short-hand tag `<? ...`. This is [not universally compatible](https://stackoverflow.com/q/200640/8620332) and it is recommended that these be replaced with more explicit `<?php`
- Wherever `split('pattern', ...)` occurred, DWD simply replaced with `preg_split('/pattern/', ...)`; check this over if there is any inconsistent behavior
