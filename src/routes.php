<?php

Route::post('/chargebee/webhook',
\ValentinFily\LaravelChargebee\Http\Controllers\WebhookController::class . '@handleWebhook')
->middleware('auth.very_basic');
