A lightweight starter template for modelling domains with a test-driven approach.

Run tests:
```
$ docker-compose run --rm tests_unit
$ docker-compose run --rm tests_acceptance
```

Installing/adding dependencies:

```
$ docker run --rm -v $(pwd):/app composer <install|update|require foo/bar>
```

xDebug (PHPStorm):

```
Preferences => Languages & Frameworks => PHP => Debug: Set port to 9002
Preferences => Languages & Frameworks => PHP => Servers:
    Add new
    Name: domain-model (matching docker/.env.test value)
    Host: localhost
    Path Mappings: map to /app
```

