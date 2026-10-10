# Finding: SQL Injection in Login Form

**ID:** SYNTH-001
**Severity:** Critical
**Tenant:** tenant-a
**Asset:** EXAMPLE_WEB_APP_1

## Description
A SQL injection vulnerability exists in the login form of EXAMPLE_WEB_APP_1.
User input in the `username` parameter is concatenated directly into a SQL query
without parameterization. An attacker can bypass authentication or extract data.

## Evidence
- Parameter: `username`
- Payload: `' OR '1'='1`
- Endpoint: `https://example.test/login`

## Impact
Full authentication bypass. Potential access to all user records and
configuration tables.

## Remediation
- Use parameterized queries.
- Validate and sanitize all inputs.
- Apply least privilege to the database account.

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
