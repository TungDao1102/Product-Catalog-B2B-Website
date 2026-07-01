# OPcache Configuration for Shared Hosting

Add the following to your `php.ini` or `.user.ini` file (if your hosting supports it):

```ini
[opcache]
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
opcache.validate_timestamps=1
```

> **Note:** If you cannot modify `php.ini`, contact your hosting provider to enable OPcache.
> On some shared hosts, OPcache is already enabled by default — check via `phpinfo()`.
