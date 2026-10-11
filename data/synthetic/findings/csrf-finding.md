# Finding: Cross-Site Request Forgery on Profile Update

**ID:** SYNTH-004
**Type:** finding
**Source:** https://owasp.org/www-community/attacks/csrf
**Tenant:** tenant-a
**Asset:** EXAMPLE_WEB_APP_1

## Description
The profile update form does not include a CSRF token. An attacker can
forge a POST request from a malicious page and modify the victim's profile
without their knowledge.

## Evidence
- Endpoint: `https://example.test/profile/update`
- Method: POST
- Missing: CSRF token, no SameSite cookie

## Impact
Account modification. If the victim is an admin, privilege escalation.

## Remediation
- Add a per-request CSRF token.
- Validate the token on every state-changing request.
- Use SameSite=Lax cookies.
- Check the Origin header.

## References
- https://owasp.org/www-community/attacks/csrf
- https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html