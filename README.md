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
- Go to http://localhost:3000 (or whatever port you set in `.env`) and enter 'admin' as user and hit enter (with no password)

## Production

In addition to the quick start instructions, you need to:

- create a `.htpasswd` file in the root of this project and enter credentials for `admin`, `user`, `view` and `util`. The format for these credentials can be seen in the `src/.htpasswd` file. To generate a new line for each user-password combo, run `htpasswd -n` on a unix command line.
