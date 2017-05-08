<spark-settings-subscription-screen inline-template>
    <div id="spark-settings-subscription-screen">
        <div v-if="userIsLoaded && plansAreLoaded">

            <!-- Current Coupon -->
            @include('erpnetSaas::settings.tabs.subscription.coupon')

                    <!-- Subscribe -->
            @include('erpnetSaas::settings.tabs.subscription.subscribe')

                    <!-- Update Subscription -->
            @include('erpnetSaas::settings.tabs.subscription.change')

                    <!-- Update Credit Card -->
            @include('erpnetSaas::settings.tabs.subscription.card')

                    <!-- Resume Subscription -->
            @include('erpnetSaas::settings.tabs.subscription.resume')

                    <!-- Invoices -->
            @if (count($invoices) > 0)
            @include('erpnetSaas::settings.tabs.subscription.invoices.vat')

            @include('erpnetSaas::settings.tabs.subscription.invoices.history')
            @endif

                    <!-- Cancel Subscription -->
            @include('erpnetSaas::settings.tabs.subscription.cancel')
        </div>

        <!-- Change Subscription Modal -->
        @include('erpnetSaas::settings.tabs.subscription.modals.change')

                <!-- Cancel Subscription Modal -->
        @include('erpnetSaas::settings.tabs.subscription.modals.cancel')
    </div>
</spark-settings-subscription-screen>
