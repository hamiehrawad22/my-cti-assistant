# Remediation Guide: Cross-Site Request Forgery (CSRF)

**Type:** csrf
**Source:** OWASP Cheat Sheet Series (adapted, synthetic example)
**Tenant:** global

## Summary
CSRF tricks a logged-in user's browser into sending unwanted requests to a
site that trusts the user's session.

## Steps
1. Add a CSRF token to every state-changing form.
2. Validate the token server-side on every request.
3. Use SameSite=Lax or SameSite=Strict cookies.
4. Check the Origin and Referer headers.
5. Log and alert on CSRF validation failures.

## References
- https://owasp.org/www-community/attacks/csrf
- https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html