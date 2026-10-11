# Remediation Guide: SQL Injection

**Type:** sqli
**Source:** OWASP Cheat Sheet Series (adapted, synthetic example)
**Tenant:** global

## Summary
SQL injection occurs when untrusted input is interpreted as SQL code.

## Steps
1. Use parameterized queries or prepared statements everywhere.
2. Never concatenate user input into SQL strings.
3. Validate input types and lengths at the boundary.
4. Use a database account with least privilege.
5. Log and alert on SQL error patterns.

## References
- https://owasp.org/www-community/attacks/SQL_Injection
- https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html
