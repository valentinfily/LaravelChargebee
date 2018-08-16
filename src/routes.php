<?php

Route::post('/chargebee/webhook',
\ValentinFily\LaravelChargebee\Http\Controllers\WebhookController::class . '@handleWebhook')
->middleare('auth.very_basic');
