# Assumptions and Risks

## Assumptions

1. Synthetic/Sanitized Data Only: All test data will be synthetic or sanitized. No real CTI findings, customer data, or secrets will be sent to AI providers.

2. Isolated Environment: The proof of concept runs outside the production path. No shared ownership of production data between Symfony and Python services.

3. Gemini Free Tier Sufficient: Gemini's free tier provides enough requests for development and evaluation. Daily limits reset at midnight Pacific Time, allowing continued free use.

4. Local Development: Development happens on a single machine with Symfony CLI, Composer, and PHP 8.2.

5. Model Stability: gemini-3.5-flash-lite will remain available for the duration of the project. If deprecated, switching models requires only a config change.

6. Single Provider Initially: Gemini is the only configured provider for the MVP. A second provider (Ollama) will be added in later weeks.

## Risks

### 1. Pre-1.0 API Changes (HIGH)
Risk: Symfony AI is in the 0.x release series. APIs may change without backward compatibility guarantees.
Status: Currently on 0.14.1. All packages are experimental.
Mitigation: Pin exact package versions in composer.json. Document all versions used. Check upgrade notes before any version change.

### 2. Non-Deterministic Model Behavior (MEDIUM)
Risk: LLM responses vary between calls, making exact-output tests unreliable.
Mitigation: Test orchestration deterministically (mock model responses). Evaluate response quality statistically via the evaluation dataset.

### 3. Scope Creep (MEDIUM)
Risk: Project expands into a full chatbot platform instead of a focused POC.
Mitigation: Protect the MVP. Defer multi-agent, write tools, and broad UI work.

### 4. Free Tier Rate Limits (MEDIUM)
Risk: Gemini free tier has daily request limits. Heavy testing may hit the cap.
Details: Free tier limits are typically 15-30 RPM and 1,500 RPD for Flash models.
Status: Already encountered a rate limit error during development.
Mitigation: Batch evaluations. Run live-model tests sparingly. Use cached/mocked responses for most tests.

### 5. Security Boundary Confusion (MEDIUM)
Risk: Model may be trusted to enforce authorization, or tools may expose unintended data.
Mitigation: Enforce all authorization outside the model, inside explicit Symfony application services. Tools are deny-by-default.

### 6. Secret Exposure (LOW)
Risk: API keys committed to source control.
Mitigation: Use .env.local (gitignored). Never hardcode keys. Consider Symfony Secret Vault for production-like setups.

### 7. No Vector Store Configured Yet (LOW)
Risk: RAG functionality delayed if store configuration is complex.
Planned: PostgreSQL pgvector.
Mitigation: Start with InMemoryStore for initial RAG testing if PostgreSQL setup is slow. Switch to PostgreSQL later.

### 8. Provider Lock-In (LOW)
Risk: Code becomes Gemini-specific, defeating the provider-portability goal.
Mitigation: Keep provider-specific code isolated in config. Use PlatformInterface and AgentInterface abstractions. Test Ollama as a second provider in later weeks.

### 9. PHP Version Compatibility (LOW)
Risk: PHP 8.2.12 may have limitations with newer AI packages.
Status: Currently working. Symfony AI requires PHP 8.2+.
Mitigation: Monitor composer requirements. Upgrade PHP if needed for later features.

### 10. Ollama Integration Complexity (LOW)
Risk: Adding Ollama as a second provider may introduce new configuration challenges.
Mitigation: Research Ollama bridge documentation before implementation. Keep provider config isolated.

## Risk Summary Table

| Risk | Likelihood | Impact | Priority | Status |
|------|-----------|--------|----------|--------|
| Pre-1.0 API changes | High | Medium | HIGH | Active |
| Scope creep | Medium | High | HIGH | Active |
| Free tier rate limits | High | Low | MEDIUM | Encountered |
| Non-deterministic behavior | High | Medium | MEDIUM | Active |
| Security boundary confusion | Low | High | MEDIUM | Active |
| Secret exposure | Low | High | LOW | Active |
| Provider lock-in | Low | Medium | LOW | Active |
| No vector store yet | Low | Low | LOW | Deferred |
| PHP compatibility | Low | Low | LOW | Monitoring |
| Ollama complexity | Low | Low | LOW | Not started |

## Version Tracking

| Component | Version | Notes |
|-----------|---------|-------|
| Symfony AI Bundle | 0.14.1 | Experimental, pin version |
| Symfony AI Agent | 0.14.1 | Experimental, pin version |
| Symfony AI Gemini Platform | 0.14.0 | Experimental, pin version |
| Symfony AI Platform | 0.14.1 | Experimental, pin version |
| Symfony Framework | 7.4.20 | Stable |
| PHP | 8.2.12 | Stable |