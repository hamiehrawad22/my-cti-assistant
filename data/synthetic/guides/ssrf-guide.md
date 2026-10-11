# Remediation Guide: Server-Side Request Forgery (SSRF)

**Type:** ssrf
**Source:** OWASP Cheat Sheet Series (adapted, synthetic example)
**Tenant:** global

## Summary
SSRF occurs when the server fetches a URL supplied by an attacker, allowing
access to internal services or cloud metadata.

## Steps
1. Use an allowlist of permitted hosts and schemes.
2. Block private IP ranges (10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16).
3. Block link-local ranges (169.254.0.0/16).
4. Resolve DNS and validate the final IP before connecting.
5. Do not follow redirects automatically.
6. Never return raw response bodies to the user.

## References
- https://owasp.org/www-community/attacks/Server_Side_Request_Forgery
- https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html