# RESTful API

POST data:

```php
$data = [
	// Document Settings
	'document' => [
		'margin' => [],
		'content' => '<h1>test</h1>'
	],
	// Engine Specific Settings
	'engine' => [
		
	]
]
```

If everything was OK you'll receive a status 200 with content type application/pdf.
