<?php
namespace ValentinFily\LaravelChargebee;


use Carbon\Carbon;

/**
 * This trait handles webhooks coming from Chargebee
 *
 * Class HandlesWebhooks
 * @package ValentinFily\LaravelChargebee
 */
trait HandlesWebhooks
{
    /**
     * Cancel a subscription instantly.
     *
     * Compatible with the following webhooks:
     * subscription_cancelled
     *
     * @return $this
     */
    public function updateCancellationDate($date = null)
    {
        $this->ends_at = ($date) ?: Carbon::now();
        $this->save();

        return $this;
    }

    /**
     * Remove subscription's cancellation date.
     *
     * Compatible with the following webhooks:
     * subscription_reactivated / subscription_scheduled_cancellation_removed
     *
     * @return $this
     */
    public function removeCancellationDate()
    {
        $this->ends_at = NULL;
        $this->save();

        return $this;
    }

    /**
     * Add scheduled changes to subscription.
     *
     * Compatible with the following webhooks:
     * subscription_changes_scheduled
     *
     * @return $this
     */
    public function scheduleChanges()
    {
        $this->scheduled_changes = 1;
        $this->save();

        return $this;
    }

    /**
     * Remove scheduled changes from subscription.
     *
     * Compatible with the following webhooks:
     * subscription_scheduled_changed_removed
     *
     * @return $this
     */
    public function removeScheduledChanges()
    {
        $this->scheduled_changes = 0;
        $this->save();

        return $this;
    }



}
