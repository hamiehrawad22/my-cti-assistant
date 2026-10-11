# Finding: Broken Access Control on Admin API

**ID:** SYNTH-003
**Severity:** High
**Tenant:** tenant-b
**Asset:** EXAMPLE_ADMIN_API

## Description
The admin API does not verify the caller's role.

## Remediation
- Enforce role checks.
- Deny by default.

## References
- https://owasp.org/Top10/A01_2021-Broken_Access_Control/