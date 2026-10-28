import assert from 'node:assert/strict'
import { describe, it } from 'node:test'

import {
  buildUserData,
  normalizeEmail,
  normalizePhoneAR,
  splitFullName,
} from '../../resources/js/lib/analytics.js'

describe('normalizeEmail', () => {
  it('trims and lowercases', () => {
    assert.equal(
      normalizeEmail('  Juan.Perez@Mail.COM '),
      'juan.perez@mail.com',
    )
  })

  it('discards values that are not an email', () => {
    assert.equal(normalizeEmail('juan'), undefined)
    assert.equal(normalizeEmail(''), undefined)
    assert.equal(normalizeEmail(undefined), undefined)
  })
})

describe('normalizePhoneAR', () => {
  const cases = [
    ['11 2233-4455', '+5491122334455'],
    ['011 15 2233-4455', '+5491122334455'],
    ['15-2233-4455 11', undefined],
    ['(0351) 15 555-1234', '+5493515551234'],
    ['351 5551234', '+5493515551234'],
    ['2954 15 123456', '+5492954123456'],
    ['+54 9 11 2233 4455', '+5491122334455'],
    ['+54 11 2233 4455', '+5491122334455'],
    ['54 9 351 555 1234', '+5493515551234'],
    ['0054 9 11 2233 4455', '+5491122334455'],
    ['9 351 555 1234', '+5493515551234'],
    ['9 11 2233-4455', '+5491122334455'],
    ['+598 99 123 456', '+59899123456'],
  ]

  for (const [input, expected] of cases) {
    it(`normalizes "${input}"`, () => {
      assert.equal(normalizePhoneAR(input), expected)
    })
  }

  it('discards numbers without area code or too short', () => {
    assert.equal(normalizePhoneAR('2233-4455'), undefined)
    assert.equal(normalizePhoneAR('123'), undefined)
    assert.equal(normalizePhoneAR(''), undefined)
    assert.equal(normalizePhoneAR(undefined), undefined)
  })
})

describe('splitFullName', () => {
  it('uses the first word as first name and the rest as last name', () => {
    assert.deepEqual(splitFullName('  Juan   Carlos Pérez '), {
      first_name: 'juan',
      last_name: 'carlos pérez',
    })
  })

  it('returns only first name for a single word', () => {
    assert.deepEqual(splitFullName('Juan'), { first_name: 'juan' })
  })

  it('returns an empty object for blank input', () => {
    assert.deepEqual(splitFullName('   '), {})
  })
})

describe('buildUserData', () => {
  it('builds the enhanced conversions payload', () => {
    assert.deepEqual(
      buildUserData({
        name: 'Juan Pérez',
        email: 'Juan@Mail.com',
        phone: '11 2233-4455',
        province: 'Buenos Aires',
        city: 'Caballito',
      }),
      {
        email: 'juan@mail.com',
        phone_number: '+5491122334455',
        address: {
          first_name: 'juan',
          last_name: 'pérez',
          region: 'Buenos Aires',
          city: 'Caballito',
          country: 'AR',
        },
      },
    )
  })

  it('omits fields that could not be normalized', () => {
    assert.deepEqual(buildUserData({ email: 'juan@mail.com', phone: '123' }), {
      email: 'juan@mail.com',
      address: { country: 'AR' },
    })
  })
})
