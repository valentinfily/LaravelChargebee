<?php

Route::post('/chargebee/webhook', ['middleware' => 'auth.very_basic'],
\ValentinFily\LaravelChargebee\Http\Controllers\WebhookController::class . '@handleWebhook');
