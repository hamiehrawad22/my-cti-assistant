# Finding: Broken Access Control on Admin API

**ID:** SYNTH-003
**Severity:** High
**Tenant:** tenant-b
**Asset:** EXAMPLE_ADMIN_API

## Description
The admin API endpoint `/api/v1/admin/users` does not verify the caller's role.
Any authenticated user can list and modify all users.

## Evidence
- Endpoint: `/api/v1/admin/users`
- Method: GET, POST, DELETE
- Expected role: `admin`
- Observed: any authenticated user succeeds

## Impact
Privilege escalation. Unauthorized read and write of user accounts.

## Remediation
- Enforce role checks in a central authorization service.
- Deny by default.
- Add tests for unauthorized access paths.
