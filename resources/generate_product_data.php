<?php declare(strict_types=1);

const DATA_URL = 'https://raw.githubusercontent.com/LIFX/products/master/products.json';

function error_exit(string $message)
{
    \fwrite(\STDERR, "ERROR: {$message}\n");
    exit(1);
}

if (!$json = \file_get_contents(DATA_URL)) {
    error_exit('Failed to retrieve data');
}

if (!$data = \json_decode($json)) {
    error_exit('Failed to decode data as JSON');
}

foreach ($data as $vendor) {
    foreach ($vendor->products as $product) {
        $key = \var_export("{$vendor->vid}:{$product->pid}", true);

        $name = \var_export($product->name, true);

        $features = \implode(' | ', \array_keys(\array_filter([
            'ProductFeatures::COLOR' => $product->features->color ?? false,
            'ProductFeatures::INFRARED' => $product->features->infrared ?? false,
            'ProductFeatures::MULTIZONE' => $product->features->multizone ?? false,
        ]))) ?: 0;

        echo "{$key} => [{$name}, {$features}],\n";
    }
}
