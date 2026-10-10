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
