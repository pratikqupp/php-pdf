# Installation

You'll need composer installed on your machine.

```bash
composer require phauthentic/pdf-service
```

To run the dev server

```bash
composer serve
```

## Security Note

The application does not care about from where it get's called!

Since this is a microservice and not thought to be accessible from the public internet, you should make sure that this service is only reachable from within your own network!
