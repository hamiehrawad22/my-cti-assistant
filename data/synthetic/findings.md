# Finding: SQL Injection in Login Form

**ID:** SYNTH-001
**Severity:** Critical
**Tenant:** tenant-a
**Asset:** EXAMPLE_WEB_APP_1

## Description
A SQL injection vulnerability exists in the login form of EXAMPLE_WEB_APP_1.

## Remediation
- Use parameterized queries.
- Validate and sanitize all inputs.

## References
- https://owasp.org/www-community/attacks/SQL_Injection

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
