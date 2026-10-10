# Finding: Reflected XSS in Search Page

**ID:** SYNTH-002
**Severity:** High
**Tenant:** tenant-a
**Asset:** EXAMPLE_WEB_APP_1

## Description
The `q` parameter on the search page is reflected into HTML without encoding.
An attacker can inject script tags via a crafted URL.

## Evidence
- Parameter: `q`
- Payload: `<script>alert(1)</script>`
- Endpoint: `https://example.test/search?q=`

## Impact
Session theft, credential phishing, page defacement in the victim's browser.

## Remediation
- HTML-encode all output.
- Add a Content-Security-Policy header.
- Validate and reject unsafe input patterns.
