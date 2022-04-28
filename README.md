# DatasetTracker

## Overview

This is a LAMP-stack application used by the Small-Bodies Node at the University of Maryland to track meta data describing their datasets. This implementation has been migrated from UMD linux servers to docker to be deployed on remote servers.

## Quick Start (local development)

- Install `docker` and `docker-compose` on your machine
- Copy `.env-template` to `.env` and edit
- You need to have a pre-populated mysql development DB
  - If you have a mysql-dump file, then you can use `_restore_mysql` to populate it
  - If you do not have a mysql-dump file, then you'll need to contact a member of the sbn team at UMD
- To run locally in dev mode: `docker-compose -f docker-compose.dev.yml up`
- To shut down in dev mode, hit CTRL-C to kill the 'up' process, and then clean up with `docker-compose -f docker-compose.dev.yml down`
- To view DatasetTracker interface go to http://localhost:5050 (or whatever port you set in `.env`) and enter 'admin' as user and hit enter (with no password)
- To view PhpMyAdmin interface go to http://localhost:5051 (or whatever port you set in `.env`) and use your mysql credentials to login

## Production

In addition to the quick start instructions, you need to:

- create a `.htpasswd` file in the root of this project and enter credentials for `admin`, `user`, `view` and `util`. The format for these credentials can be seen in the `src/.htpasswd` file. To generate a new line for each user-password combo, run `htpasswd -n admin`, etc. on a unix command line, enter a password, and copy the output to a new line within the `.htpasswd` file.
- Begin daemonized docker containers in production mode with `docker-compose -f docker-compose.prod.yml up -d`
- End daemonized docker containers in production mode with `docker-compose -f docker-compose.prod.yml down`
- Follow instructions within `./ssl-config/README.md` to enable SSL connections

## Utils

When developing/debugging your docker containers, you can use the script `_docker_enterer` to quickly jump into a running container

## Backups

The script `_backup_mysql` will make a logical backup of the mysql DB to a dir specified by `$BACKUP_DIR` within `.env` AND remove obsolete backups. This script is supposed to be run via a daily cronjob, and the backup dir is intended to be mounted to an AWS S3 bucket.
