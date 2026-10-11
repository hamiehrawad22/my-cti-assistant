# Remediation Guide: Cross-Site Scripting (XSS)

**Type:** xss
**Source:** OWASP Cheat Sheet Series (adapted, synthetic example)
**Tenant:** global

## Summary
XSS occurs when untrusted input is rendered as HTML or JavaScript.

## Steps
1. HTML-encode all output by default.
2. Use context-aware escaping (HTML, attribute, JS, URL).
3. Add a strict Content-Security-Policy.
4. Validate and reject dangerous input patterns.
5. Use modern frameworks that auto-escape by default.

## References
- https://owasp.org/www-community/attacks/xss/
