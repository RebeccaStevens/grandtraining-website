<paper-toast id="caching-complete" duration="6000"
  text="Caching complete! You can now use this app offline.">
</paper-toast>

<platinum-sw-register
  auto-register
  clients-claim
  skip-waiting
  on-service-worker-installed="onServiceWorkerInstalled">
  <platinum-sw-cache
    default-cache-strategy="networkFirst"
    cache-config-file="cache-config.json">
  </platinum-sw-cache>
</platinum-sw-register>
