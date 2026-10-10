# MVP Scope — Meanings

## Purpose
This document keeps the Week 1 to Week 8 MVP focused. Anything not in "In Scope" needs an ADR before it can be added.



## In Scope (MVP, section 7.1)

1. Isolated Symfony or API Platform application or module.
Means: Build in a separate project or module. Do not touch production code.

2. One hosted model provider, Gemini.
Means: Use only Gemini for now. No second provider yet.

3. Structured finding assessment with validation.
Means: AI must return data in a fixed shape (DTO). Validate it before using. Reject invalid output.

4. Small RAG knowledge base with source-grounded answers.
Means: Index a few documents. AI answers using those documents only. Answer must include source references.

5. At least two read-only CTI tools.
Means: Build 2 of the 5 tools from the brief. They only read data. Never write.

6. Latency and token-usage logging.
Means: Record how long each AI call takes and how many tokens it used.

7. Unit and functional tests.
Means: Write tests for your code logic (unit) and for the full request flow (functional).

8. Initial evaluation dataset and results report.
Means: Create 30–50 test cases. Run them. Report the scores.



## Full Internship Scope (section 7.2, after MVP)

1. Second provider such as Ollama or an OpenAI-compatible endpoint.
Means: Add a second AI provider. Prove the code is portable.

2. Persisted conversation sessions with retention rules.
Means: Save chat history. Define how long it is kept. Delete after that period.

3. Up to five CTI tools with authorization boundaries.
Means: Build all 5 tools. Each tool checks permissions before returning data.

4. Prompt-injection and tenant-isolation test cases.
Means: Test that malicious input fails safely. Test that tenants cannot see each other's data.

5. Repeatable evaluation CLI or dashboard.
Means: A command or small UI that reruns the evaluation suite and prints scores.

6. Docker Compose dev environment and CI pipeline.
Means: Package the app in containers. Run tests automatically on every push.

7. Architecture Decision Record and final recommendation.
Means: Write the final document: adopt, adopt with changes, experimental only, or do not adopt.



## Stretch Objectives (section 7.3, only if time allows)

1. Consume tools from an existing CTI MCP server.
Means: Connect to an external MCP server that already has CTI tools.

2. Expose Symfony capabilities through the MCP Bundle.
Means: Make your Symfony services available as MCP tools for others.

3. Write tool requiring explicit human confirmation.
Means: A tool that changes data, but only after a human approves.

4. Async document indexing via Symfony Messenger.
Means: Index documents in the background. Do not block the request.

5. Compare the same slice with a Python or FastAPI implementation.
Means: Build the same feature in Python. Compare results and complexity.

6. Analyst feedback loop for offline evaluation.
Means: Let analysts rate answers. Use those ratings to improve evaluation.



## Out of Scope

1. Multi-agent orchestration.
Means: Multiple AI agents talking to each other. Not allowed yet.

2. Autonomous actions.
Means: AI doing things without human approval. Not allowed.

3. Unrestricted database tools.
Means: AI running raw SQL or accessing any table. Not allowed.

4. Direct production integration.
Means: Connecting to the real production system. Not allowed.

5. Broad migration of the existing Python chatbot.
Means: Do not rewrite the Python chatbot. Leave it alone.

6. Web UI or chat frontend beyond minimal.
Means: No fancy chat interface. A simple endpoint is enough.

7. User authentication system.
Means: Do not build login or signup. Use existing auth if needed.

8. Conversation persistence until section 7.2.
Means: Do not save chat history yet. Wait until after MVP.



## Rules

1. Any new feature must be added to In Scope via PR review.
Means: Want to add something? Update this document. Open a PR. Get approval.

2. Out of Scope items need an ADR before being picked up.
Means: Want to build something out of scope? Write an ADR first.

3. MVP must ship before section 7.2 work begins.
Means: Finish all MVP items before starting full scope items.

4. Stretch objectives are considered only after MVP and section 7.2.
Means: Stretch items are last priority. Do them only if everything else is done.


## Current Status

Week 1 Setup: Complete.
Means: Minimal example works. Documentation done.

MVP (section 7.1): In progress.
Means: Working on MVP items now.

Full Scope (section 7.2): Not started.
Means: Have not begun section 7.2 items.

Stretch (section 7.3): Not started.
Means: Have not begun stretch items.