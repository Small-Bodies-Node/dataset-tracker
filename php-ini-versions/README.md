# What's php-ini-versions?

These files are the default ini files taken from the php-apache containers. They kept here for easy reference. The Dockerfiles edit them.

You copy them from the running container with:

```bash
docker cp \
  <CONTAINERID>:/usr/share/php5/php.ini-development \
  ./php.ini-development ./php-ini-versions
```
