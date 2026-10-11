# Finding: Reflected XSS in Search Page

**ID:** SYNTH-002
**Severity:** High
**Tenant:** tenant-a
**Asset:** EXAMPLE_WEB_APP_1

## Description
The `q` parameter on the search page is reflected without encoding.

## Remediation
- HTML-encode all output.
- Add Content-Security-Policy.

## References
- https://owasp.org/www-community/attacks/xss