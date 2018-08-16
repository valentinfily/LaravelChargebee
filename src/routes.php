<?php

Route::post('/chargebee/webhook', ['middleware' => 'shield']
\ValentinFily\LaravelChargebee\Http\Controllers\WebhookController::class . '@handleWebhook');
