// Marketing attribution for landing leads: click IDs and UTMs captured from the
// URL, kept in localStorage so a returning visitor is still attributed to the
// last paid/tagged touch.

export const ATTRIBUTION_PARAMS = [
  'gclid',
  'gbraid',
  'wbraid',
  'fbclid',
  'utm_source',
  'utm_medium',
  'utm_campaign',
  'utm_term',
  'utm_content',
]

const PAYLOAD_FIELDS = [...ATTRIBUTION_PARAMS, 'landing_url', 'referrer']

const STORAGE_KEY = 'ppa_attribution'
const MAX_AGE_MS = 90 * 24 * 60 * 60 * 1000

// Returns null when the URL carries no attribution param, so a direct or
// organic visit never overwrites a previously stored touch.
export function parseAttribution(search, { href, referrer, now }) {
  const params = new URLSearchParams(search)
  const attribution = {}

  for (const param of ATTRIBUTION_PARAMS) {
    const value = params.get(param)?.trim()
    if (value) attribution[param] = value
  }

  if (Object.keys(attribution).length === 0) return null

  attribution.landing_url = href
  if (referrer) attribution.referrer = referrer
  attribution.captured_at = now

  return attribution
}

export function resolveAttribution(
  stored,
  current,
  now,
  maxAgeMs = MAX_AGE_MS,
) {
  if (current) return current
  if (stored && now - stored.captured_at <= maxAgeMs) return stored
  return null
}

export function attributionPayload(attribution) {
  if (!attribution) return {}

  const payload = {}
  for (const field of PAYLOAD_FIELDS) {
    if (attribution[field] !== undefined) payload[field] = attribution[field]
  }
  return payload
}

function readStored() {
  try {
    const raw = window.localStorage.getItem(STORAGE_KEY)
    return raw ? JSON.parse(raw) : null
  } catch {
    return null
  }
}

function writeStored(attribution) {
  try {
    if (attribution) {
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(attribution))
    } else {
      window.localStorage.removeItem(STORAGE_KEY)
    }
  } catch {
    // Storage unavailable (private mode, blocked cookies): keep going without it.
  }
}

export function captureAttribution() {
  const now = Date.now()
  const stored = readStored()
  const current = parseAttribution(window.location.search, {
    href: window.location.href,
    referrer: document.referrer,
    now,
  })
  const resolved = resolveAttribution(stored, current, now)

  if (current) writeStored(current)
  else if (stored && !resolved) writeStored(null)

  return resolved
}
