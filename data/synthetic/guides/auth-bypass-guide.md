# Remediation Guide: Authentication Bypass

**Type:** auth-bypass
**Source:** OWASP Cheat Sheet Series (adapted, synthetic example)
**Tenant:** global

## Summary
Authentication bypass lets an attacker access protected resources without valid credentials.

## Steps
1. Centralize authentication in one service.
2. Never trust client-supplied role or user identifiers.
3. Enforce authorization on every request, deny by default.
4. Add rate limiting and account lockout.
5. Test negative cases (no token, wrong role, expired token).

## References
- https://owasp.org/Top10/A07_2021-Identification_and_Authentication_Failures/
