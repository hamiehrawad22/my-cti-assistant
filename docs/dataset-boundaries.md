# Week 1 — Dataset Boundaries (Very Simple)

## Purpose

Say clearly what data is allowed in the POC, especially data sent to the model.


## Allowed Data (safe to use)

- Synthetic findings: fake data we create ourselves, no real IDs.

- Sanitized historical findings: real findings but with names, IPs, and tenant info removed.


- Public security guides: NVD, CVE, vendor advisories.
  - Ex: A CVE page anyone can read online.

- Public standards: OWASP, NIST, MITRE ATT&CK.
  - Ex: The OWASP Top 10 list.

- Product and API docs: our own public docs.
  - Ex: Docs already on our public website.

- Analyst notes that are sanitized: rewritten to remove PII.
  - Ex: A note with all names and IPs replaced.



## Forbidden Data (never allowed)

- Real customer data: never.
  - Ex: Real customer names or tickets.

- Real CTI findings: never.
  - Ex: Real Cyber Threat Intelligence from production.

- Secrets and API keys: never.
  - Ex: Passwords, tokens, `.env` values.

- Personal data (PII): never.
  - Ex: Names, emails, phone numbers.

- Production database exports: never.
  - Ex: A copy of the live database.

- Internal-only docs: never.
  - Ex: Private wiki pages.

- Customer identifiers: never.
  - Ex: Customer IDs, account numbers.


## Storage Rules

- Test fixtures live in `tests/Fixtures/Synthetic/`.
  - Ex: All fake test files go in this folder.

- Vector store is indexed only from `data/synthetic/`.
  - Ex: Only files in this folder get searched.

- No `.env` secrets inside fixtures.
  - Ex: No API keys inside test files.

- No logs of prompts or retrieved content by default.
  - Ex: We don't save what we send to the model.



## Sanitization Checklist

Before adding any document:

- Names replaced with `EXAMPLE_` prefix.
  - Ex: `John Smith` → `EXAMPLE_USER_1`.

- IPs replaced with RFC 5737 ranges such as `192.0.2.0/24`.
  - Ex: `10.0.0.5` → `192.0.2.5`.

- Tenant IDs replaced with `tenant-a` or `tenant-b`.
  - Ex: `acme-corp` → `tenant-a`.

- No emails, no hostnames, no real ticket IDs.
  - Ex: Remove `user@acme.com`.

- No internal URLs.
  - Ex: Remove `https://internal.company.local/...`.



## Enforcement (how we check the rules)

- Pre-commit hook: `gitleaks protect --staged`.
  - Ex: Blocks secrets before you commit code.

- CI: scan `tests/Fixtures/` for forbidden patterns.
  - Ex: Pipeline fails if a real email is found.

- Every PR that changes files in tests/Fixtures/Synthetic/ must be reviewed and approved by another developer before it can be merged.


## Retention (how long we keep data)

- Fixtures kept for the duration of the internship.
  - Ex: Kept until the internship ends.

.
- Deleted at the end, unless someone says "keep it".


