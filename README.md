# Nimbella SDK for PHP

A PHP library to interact with Nimbella.com services.

## Installation

```
composer require nimbella/nimbella
```

## Usage

```php
use Nimbella\Nimbella;

$nim = new Nimbella();

// Get configured \Predis|Client (https://github.com/predis/predis).
$redis = $nim->redis();
$redis->set('foo', 'bar');
$value = $redis->get('foo');

// Get a configured Google\Cloud\Storage\Bucket (https://github.com/googleapis/google-cloud-php-storage).
$bucket = $nim->storage();
// Upload a file to the bucket.
$bucket->upload(
    fopen('/data/file.txt', 'r')
);
```

## Support

We're always happy to help you with any issues you encounter. You may want to [join our Slack community](https://nimbella-community.slack.com/) to engage with us for a more rapid response.

## License

Apache-2.0. See [LICENSE](LICENSE) to learn more.
