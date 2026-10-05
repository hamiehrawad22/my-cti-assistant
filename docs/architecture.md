# Week 1 — Architecture (POC) — Simple Version



## Scope 

- Isolated proof of concept — a small separate test. If it breaks, nothing else breaks.
- No production data — use fake/sample data only.
- No shared database with Python/FastAPI — Symfony and Python stay separate.



## Components 

1. Client — CLI in Week 1, API endpoint from Week 3.
   - Whoever sends the question. Week 1 = terminal command. Week 3 = web URL.

2. Symfony App — owns authentication, validation, orchestration.
   - The manager. Checks who you are, checks data is correct, and coordinates all steps.

3. Symfony AI Agent — prompt handling and tool selection.
   - The coordinator. Builds the prompt and decides which tools to use.

4. Gemini Platform — external LLM provider.
   - The language model (outside your system). Generates the answer text.

5. Vector Store — InMemoryStore now, pgvector later.
   - The document search. Finds docs by meaning (similarity search). Temporary now, real DB later.

6. Read-only Tools — Symfony services wrapping domain calls.
   - The data fetchers. Fetch data. Can only read, never change.

7. Existing Domain APIs — untouched, consumed via read-only calls.
   - The existing system with real data. We only read from it, never modify it.

8. Python/FastAPI AI layer — untouched during the experiment.
   - The old AI system. Keeps running as-is. No changes.



## Data Flow (the journey)

1. Client submits a question and a finding ID.
   - User sends a question plus an ID to look up.

2. Symfony authenticates and authorizes outside the model.
   - Symfony checks login + permission. The AI never decides this.

3. Application service builds the agent context.
   - Gathers background info (tenant, history, settings).

4. Agent retrieves from the vector store and invokes allowed tools.
   - Agent searches similar docs + calls allowed tools.

5. Tools call authorized Symfony services using deny-by-default.
   - Deny-by-default = if not explicitly allowed, it's blocked.

6. Model returns a structured response.
   - Structured = proper format (JSON), not free text.

7. Symfony validates the response using a DTO.
   - DTO = Data Transfer Object

8. Sources and metrics are attached, then returned.
   - Sources = which docs the answer came from. Metrics = speed, cost.



## Trust Boundaries 

- Client to Symfony: authentication required.
  - Only logged-in users can send requests.

- Symfony to Model: synthetic data only.
  - Send fake data to Gemini, never real customer data.

- Model to Tools: allowlist only.
  - Only pre-approved tools work.

- Tools to Domain: read-only and authorized.
  - Tools can read, not write.

- Store to Tenant data: filtered by tenant.
  - Company A can't see Company B's data.



## What Symfony Owns in the POC

- Chat sessions — conversation history.
- Prompt configuration — templates for the AI.
- Evaluation cases — test Q&A for quality.
- Metrics — speed, cost, accuracy.


## What Stays Elsewhere

- Domain data stays in the main app.
- Python chatbot stays unchanged.



## Non-Goals (what we are NOT doing)

- Multi-agent orchestration — no team of AIs. Just one.
- Write tools — no tools that change data.
- Production integration — not going live yet.
- Python layer migration — not moving Python into Symfony.

