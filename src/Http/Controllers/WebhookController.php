<?php

namespace ValentinFily\LaravelChargebee\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ValentinFily\LaravelChargebee\Subscription;
use App\Events\ChargebeeWebhookReceivedEvent;

/**
 * Class WebhookController
 * @package ValentinFily\LaravelChargebee\Http\Controllers
 */
class WebhookController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $webhookEvent = studly_case($request->event_type);

        if (method_exists($this, 'handle' . $webhookEvent)) {
            $payload = json_decode(json_encode($request->input('content')));
            $type = $request->event_type;

            return $this->{'handle' . $webhookEvent}($payload, $type);
        } else {
            return response('No event handler for ' . $webhookEvent, 200);
        }
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionChanged($payload, $type)
    {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->refreshDatabaseCache();

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionCancelled($payload, $type)
    {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->updateCancellationDate(
                $payload->subscription->cancelled_at
            );

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionReactivated($payload, $type)
    {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->removeCancellationDate();

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionCancellationScheduled($payload, $type)
    {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->updateCancellationDate(
                $payload->subscription->cancelled_at
            );

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionScheduledCancellationRemoved(
        $payload,
        $type
    ) {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->removeCancellationDate();

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionChangesScheduled($payload, $type)
    {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->scheduleChanges();

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionScheduledChangesRemoved($payload, $type)
    {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->removeScheduledChanges();

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $payload
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function handleSubscriptionDeleted($payload, $type)
    {
        $subscription = $this->getSubscription($payload->subscription->id);

        if ($subscription) {
            $subscription->deleteWithAddons();

            if (class_exists('App\Events\ChargebeeWebhookReceivedEvent')) {
                event(
                    new ChargebeeWebhookReceivedEvent(
                        $payload,
                        $type,
                        $subscription->user_id
                    )
                );
            }
        }

        return response('Webhook handled successfully.', 200);
    }

    /**
     * @param $subscriptionId
     * @return mixed
     */
    protected function getSubscription($subscriptionId)
    {
        $subscription = (new Subscription())
            ->where('subscription_id', $subscriptionId)
            ->latest()->get()->first();

        return $subscription;
    }
}
