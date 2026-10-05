# Assumptions and Risks

## Assumptions

1. Synthetic and Sanitized Data Only
Means: All test data will be fake or cleaned. No real findings, customer data, or secrets sent to AI providers.

2. Isolated Environment
Means: This POC runs outside production. Symfony and Python do not share production data.

3. Gemini Free Tier Sufficient
Means: Free tier gives enough requests for development and testing. Limits reset daily at midnight Pacific Time.

4. Local Development
Means: Everything runs on one machine with Symfony CLI, Composer, and PHP 8.2.

5. Model Stability
Means: gemini-3.5-flash-lite will stay available during the project. If it is removed, only the config changes.

6. Single Provider Initially
Means: Only Gemini is configured for the MVP. Ollama comes later.



## Risks

### 1. Pre-1.0 API Changes (HIGH)

Risk: Symfony AI is 0.x. APIs can change without backward compatibility.

Status: On 0.14.x. All packages experimental. Versions pinned in composer.json.

Mitigation: Pin exact versions. Document all versions. Check upgrade notes before any version change.

### 2. Non-Deterministic Model Behavior (MEDIUM)

Risk: LLM responses vary between calls. Exact-output tests are unreliable.

Mitigation: Test orchestration with mock responses. Evaluate quality statistically via the evaluation dataset.

3. Scope Creep (MEDIUM)
Risk: The project becomes a full chatbot platform instead of a small proof of concept.

Mitigation: MVP.md lists what is allowed and what is not allowed. Postpone multi-agent, write tools, and full UI until later.

### 4. Free Tier Rate Limits (MEDIUM)

Risk: Gemini free tier has daily request limits. Heavy testing may hit the cap.

Details: Free tier limits are typically 15 to 30 RPM and 1,500 RPD for Flash models.

Status: Already encountered a rate limit error during development.

Mitigation: Batch evaluations. Run live-model tests sparingly. Use cached or mocked responses for most tests.

### 5. Security Boundary Confusion (MEDIUM)

Risk: Model trusted to enforce authorization. Tools may expose unintended data.

Mitigation: Enforce all authorization outside the model, inside explicit Symfony services. Tools deny by default.

### 6. Secret Exposure (LOW)

Risk: API keys committed to source control.

Status: Resolved. Both .env and .env.local are gitignored. No secrets in git history.

Mitigation: Never hardcode keys. Consider Symfony Secret Vault for production-like setups.

### 7. No Vector Store Configured Yet (LOW)

Risk: RAG delayed if store configuration is complex.

Planned: PostgreSQL pgvector.

Status: InMemoryStore installed via symfony/ai-store 0.14.0. PostgreSQL migration deferred.

Mitigation: Start with InMemoryStore for initial RAG testing. Switch to PostgreSQL later.

### 8. Provider Lock-In (LOW)

Risk: Code becomes Gemini-specific. Provider portability goal fails.

Mitigation: Keep provider code isolated in config. Use PlatformInterface and AgentInterface. Test Ollama later.

### 9. PHP Version Compatibility (LOW)

Risk: PHP 8.2.12 may have limitations with newer AI packages.

Status: Working now. Symfony AI requires PHP 8.2 or higher.

Mitigation: Monitor composer requirements. Upgrade PHP if needed.

### 10. Ollama Integration Complexity (LOW)

Risk: Adding Ollama may introduce new configuration challenges.

Mitigation: Research Ollama bridge docs first. Keep provider config isolated.


## Version Tracking

- Symfony AI Bundle 0.14.1 — Experimental, pinned.
- Symfony AI Agent 0.14.1 — Experimental, pinned.
- Symfony AI Gemini Platform 0.14.0 — Experimental, pinned.
- Symfony AI Platform 0.14.1 — Experimental, pinned.
- Symfony AI Store 0.14.0 — Experimental, pinned.
- Symfony Framework 7.4.20 — Stable.
- PHP 8.2.12 — Stable.