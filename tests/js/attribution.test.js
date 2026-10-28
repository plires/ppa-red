import assert from 'node:assert/strict'
import { describe, it } from 'node:test'

import {
  ATTRIBUTION_PARAMS,
  attributionPayload,
  parseAttribution,
  resolveAttribution,
} from '../../resources/js/lib/attribution.js'

const DAY = 24 * 60 * 60 * 1000
const NOW = 1_800_000_000_000

describe('parseAttribution', () => {
  it('keeps the present params plus landing url, referrer and timestamp', () => {
    assert.deepEqual(
      parseAttribution('?utm_source=google&gclid=abc&utm_term=&foo=bar', {
        href: 'https://ppared.com.ar/?utm_source=google&gclid=abc',
        referrer: 'https://www.google.com/',
        now: NOW,
      }),
      {
        gclid: 'abc',
        utm_source: 'google',
        landing_url: 'https://ppared.com.ar/?utm_source=google&gclid=abc',
        referrer: 'https://www.google.com/',
        captured_at: NOW,
      },
    )
  })

  it('omits an empty referrer', () => {
    const result = parseAttribution('?fbclid=xyz', {
      href: 'https://ppared.com.ar/?fbclid=xyz',
      referrer: '',
      now: NOW,
    })
    assert.equal('referrer' in result, false)
    assert.equal(result.fbclid, 'xyz')
  })

  it('returns null for a direct or organic visit', () => {
    assert.equal(
      parseAttribution('', {
        href: 'https://ppared.com.ar/',
        referrer: 'https://www.google.com/',
        now: NOW,
      }),
      null,
    )
    assert.equal(
      parseAttribution('?foo=bar&utm_source=', {
        href: 'x',
        referrer: '',
        now: NOW,
      }),
      null,
    )
  })

  it('recognizes every attribution param', () => {
    const search = '?' + ATTRIBUTION_PARAMS.map(p => `${p}=v`).join('&')
    const result = parseAttribution(search, {
      href: 'h',
      referrer: '',
      now: NOW,
    })
    for (const param of ATTRIBUTION_PARAMS) {
      assert.equal(result[param], 'v')
    }
  })
})

describe('resolveAttribution', () => {
  const stored = { utm_source: 'old', captured_at: NOW - 10 * DAY }
  const current = { utm_source: 'new', captured_at: NOW }

  it('prefers the current touch', () => {
    assert.equal(resolveAttribution(stored, current, NOW), current)
  })

  it('falls back to a stored touch that has not expired', () => {
    assert.equal(resolveAttribution(stored, null, NOW), stored)
  })

  it('discards a stored touch older than the max age', () => {
    const old = { utm_source: 'old', captured_at: NOW - 91 * DAY }
    assert.equal(resolveAttribution(old, null, NOW), null)
    assert.equal(resolveAttribution(stored, null, NOW, 5 * DAY), null)
  })

  it('returns null when there is nothing', () => {
    assert.equal(resolveAttribution(null, null, NOW), null)
  })
})

describe('attributionPayload', () => {
  it('keeps only the backend fields', () => {
    assert.deepEqual(
      attributionPayload({
        gclid: 'abc',
        landing_url: 'https://ppared.com.ar/',
        captured_at: NOW,
        unexpected: 'x',
      }),
      { gclid: 'abc', landing_url: 'https://ppared.com.ar/' },
    )
  })

  it('returns an empty object for null', () => {
    assert.deepEqual(attributionPayload(null), {})
  })
})
