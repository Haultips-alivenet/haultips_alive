<?php

return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'My_ApiKey',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAZLnw4BA:APA91bEt7-Hts8J2v8yecvCwOtscsEZLW9p92Ew0E6dxF-QNandDuY_Qde0OPP1m4aCXqgD4EXjaqlXIBpK2bGWBodjXsW8Lh8VUJnXbbFFZPa0ij4VWvjy9dO4QCaPejBuqBY_0HfUZ',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
      'passPhrase' => '1234', //Optional
      'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
      'dry_run' => true
  ]
];