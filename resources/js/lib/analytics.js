// Publishes an event to the GTM dataLayer. Safe to call when GTM is not loaded
// (local, dashboard): the event just stays in the array and nobody reads it.
export function pushDataLayerEvent(event, payload = {}) {
  window.dataLayer = window.dataLayer || []
  window.dataLayer.push({ event, ...payload })
}

// Drops keys whose value is undefined or an empty string.
function compact(object) {
  return Object.fromEntries(
    Object.entries(object).filter(
      ([, value]) => value !== undefined && value !== '',
    ),
  )
}

export function normalizeEmail(value) {
  const email = String(value ?? '')
    .trim()
    .toLowerCase()

  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) ? email : undefined
}

// Normalizes an Argentine phone to E.164 (+549 + area code + number), the
// format Google needs to match enhanced conversions. Users type it in many
// ways ("011 15 2233-4455", "(0351) 555-1234", "+54 9 11 ..."), so this strips
// the international prefix, the trunk "0" and the mobile "15". Numbers that
// cannot be resolved to 10 national digits are discarded rather than guessed.
export function normalizePhoneAR(value) {
  const raw = String(value ?? '').trim()
  let digits = raw.replace(/\D/g, '')
  let international = raw.startsWith('+')

  if (digits.startsWith('00')) {
    digits = digits.slice(2)
    international = true
  }

  if (international || (digits.startsWith('54') && digits.length >= 12)) {
    if (!digits.startsWith('54')) {
      return digits.length >= 8 ? `+${digits}` : undefined
    }
    digits = digits.slice(2)
    if (digits.startsWith('9')) digits = digits.slice(1)
  }

  // Mobile "9" typed without the country code ("9 351 555 1234").
  if (digits.length === 11 && digits.startsWith('9')) digits = digits.slice(1)

  if (digits.startsWith('0')) digits = digits.slice(1)

  // Area codes are 2 (only "11"), 3 or 4 digits; the mobile "15" follows them.
  if (digits.length === 12) {
    for (const areaLength of [2, 3, 4]) {
      if (areaLength === 2 && !digits.startsWith('11')) continue
      if (digits.slice(areaLength, areaLength + 2) === '15') {
        digits = digits.slice(0, areaLength) + digits.slice(areaLength + 2)
        break
      }
    }
  }

  if (digits.length !== 10 || !/^(11|[23])/.test(digits)) return undefined

  // The form asks for "Teléfono / WhatsApp", so numbers are assumed mobile.
  return `+549${digits}`
}

// The form has a single name field: first word is the first name, the rest
// is the last name.
export function splitFullName(value) {
  const [firstName, ...rest] = String(value ?? '')
    .trim()
    .toLowerCase()
    .split(/\s+/)

  return compact({ first_name: firstName, last_name: rest.join(' ') })
}

// Builds the user_data object read by GTM's "User-Provided Data" variable for
// Google Ads enhanced conversions. Google hashes it before sending; it must
// never be mapped to GA4 event parameters (PII is forbidden there).
export function buildUserData({ name, email, phone, province, city } = {}) {
  return compact({
    email: normalizeEmail(email),
    phone_number: normalizePhoneAR(phone),
    address: {
      ...splitFullName(name),
      ...compact({ region: province?.trim(), city: city?.trim() }),
      country: 'AR',
    },
  })
}
