// Publishes an event to the GTM dataLayer. Safe to call when GTM is not loaded
// (local, dashboard): the event just stays in the array and nobody reads it.
export function pushDataLayerEvent(event, payload = {}) {
  window.dataLayer = window.dataLayer || []
  window.dataLayer.push({ event, ...payload })
}
