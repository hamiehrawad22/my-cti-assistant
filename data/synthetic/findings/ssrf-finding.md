# Finding: Server-Side Request Forgery on Avatar Import

**ID:** SYNTH-005
**Type:** finding
**Source:** https://owasp.org/www-community/attacks/Server_Side_Request_Forgery
**Tenant:** tenant-a
**Asset:** EXAMPLE_WEB_APP_2

## Description
The avatar import feature fetches images from a user-supplied URL without
validating the destination. An attacker can point the server at internal
services or cloud metadata endpoints.

## Evidence
- Parameter: `avatar_url`
- Payload: `http://169.254.169.254/latest/meta-data/`
- Endpoint: `https://example.test/profile/import-avatar`

## Impact
Internal service enumeration, cloud metadata theft, access to internal
admin panels.

## Remediation
- Allowlist external hosts.
- Block private IP ranges.
- Use a dedicated fetch service.
- Do not return raw response bodies to the user.

## References
- https://owasp.org/www-community/attacks/Server_Side_Request_Forgery
- https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html