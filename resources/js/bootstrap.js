import axios from 'axios'

window.axios = axios

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/*
 * Instrumentation for the random Inertia TypeError on /dashboard/form_submissions.
 *
 * Inertia treats any response carrying the `x-inertia` header as a valid page
 * and goes straight to `setPage()`, which reads `pageResponse.url`. When the
 * body is not a parseable JSON object, `getDataFromResponse()` hands back the
 * raw string, `url` is undefined, and `hrefToUrl()` blows up on
 * `undefined.toString()` — the exact error seen in production.
 *
 * Note this cannot be an nginx error page: those lack the `x-inertia` header,
 * so Inertia would fire its `invalid` event and show the error modal instead.
 * The response has to come from Laravel with a broken body — a truncated
 * response being the leading suspect.
 *
 * The interceptor only observes: it reports what arrived and lets the response
 * through, so the recovery reload in app.jsx keeps working as before.
 */

const DIAGNOSTICS_PATH = '/dashboard/client-diagnostics'
const EXCERPT_LIMIT = 1000

function readCookie(name) {
    const match = document.cookie.match(new RegExp(`(^|;\\s*)${name}=([^;]*)`))

    return match ? decodeURIComponent(match[2]) : null
}

/*
 * Inertia issues its requests with `responseType: 'text'` on purpose, to delay
 * JSON.parse until the page is actually needed (see @inertiajs/core, the axios
 * call in Request.send). So the body always arrives here as a string, and
 * parsing it again would duplicate the exact work Inertia avoids — on the
 * dashboard index, the largest response in the app.
 *
 * These two structural checks cover the failure modes without parsing:
 *   - a truncated body does not close its outermost brace,
 *   - a well-formed body with no `url` key is not a page Inertia can navigate to.
 */
function looksLikeInertiaPage(rawBody) {
    if (typeof rawBody !== 'string') {
        return (
            typeof rawBody === 'object' &&
            rawBody !== null &&
            typeof rawBody.url === 'string'
        )
    }

    const trimmed = rawBody.trim()

    if (!trimmed.startsWith('{') || !trimmed.endsWith('}')) {
        return false
    }

    return trimmed.includes('"url":')
}

function reportMalformedPage(response) {
    const rawBody =
        typeof response.data === 'string'
            ? response.data
            : (response.request?.responseText ?? '')
    const contentLength = response.headers?.['content-length']

    const payload = {
        kind: 'inertia_malformed_page',
        page_url: window.location.href,
        request_url: response.config?.url
            ? new URL(response.config.url, window.location.origin).href
            : null,
        status: response.status,
        content_type: response.headers?.['content-type'] ?? null,
        content_length_header: contentLength ? Number(contentLength) : null,
        received_length: rawBody.length,
        body_head: rawBody.slice(0, EXCERPT_LIMIT),
        body_tail: rawBody.slice(-EXCERPT_LIMIT),
    }

    // Fire and forget: failing to report must never make the original problem
    // worse. This request goes through the interceptor too, but the
    // DIAGNOSTICS_PATH guard below keeps it from reporting itself.
    axios
        .post(DIAGNOSTICS_PATH, payload, {
            headers: { 'X-XSRF-TOKEN': readCookie('XSRF-TOKEN') },
        })
        .catch(() => {})
}

function inspectResponse(response) {
    if (!response || !response.headers?.['x-inertia']) {
        return
    }

    if (response.config?.url?.includes(DIAGNOSTICS_PATH)) {
        return
    }

    // A 409 with `x-inertia-location` is Inertia's asset-version redirect and
    // legitimately carries no page body.
    if (response.status === 409 && response.headers?.['x-inertia-location']) {
        return
    }

    if (looksLikeInertiaPage(response.data)) {
        return
    }

    reportMalformedPage(response)
}

// Error responses reach the same code path in Inertia (Request.send catches
// them and still calls Response.handle()), so a broken body crashes there too.
window.axios.interceptors.response.use(
    (response) => {
        inspectResponse(response)

        return response
    },
    (error) => {
        inspectResponse(error?.response)

        return Promise.reject(error)
    },
)
