<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/api/v1/forms/subscribe/submit', 'POST', [
    'email' => 'test@example.com'
]);
$request->headers->set('Accept', 'application/json');

$response = $kernel->handle($request);

echo "Subscribe Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";

$request2 = Illuminate\Http\Request::create('/api/v1/forms/consultation/submit', 'POST', [
    'full_name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '1234567890',
    'service_interest' => 'Landscaping',
    'budget' => '$10k-$20k',
    'project_details' => 'Testing form submission'
]);
$request2->headers->set('Accept', 'application/json');

$response2 = $kernel->handle($request2);

echo "Consultation Status: " . $response2->getStatusCode() . "\n";
echo "Content: " . $response2->getContent() . "\n";
