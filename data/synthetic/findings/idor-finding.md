# Finding: Insecure Direct Object Reference on Invoice Download

**ID:** SYNTH-006
**Type:** finding
**Source:** https://owasp.org/Top10/A01_2021-Broken_Access_Control/
**Tenant:** tenant-b
**Asset:** EXAMPLE_BILLING_API

## Description
The invoice download endpoint accepts a numeric invoice ID and returns the
PDF without checking ownership. Any authenticated user can read any invoice
by changing the ID.

## Evidence
- Endpoint: `/api/v1/invoices/{id}/download`
- Method: GET
- Tested IDs: `1001`, `1002`, `1003` — all returned other users' invoices

## Impact
Cross-customer data exposure. Confidential billing information leaked.

## Remediation
- Enforce authorization on every request.
- Deny by default.
- Use opaque identifiers (UUIDs) instead of sequential IDs.
- Log and alert on cross-tenant access attempts.

## References
- https://owasp.org/Top10/A01_2021-Broken_Access_Control/