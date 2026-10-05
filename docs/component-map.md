# Symfony AI — Component Map 

## Purpose

Map what each Symfony AI package does in this POC.

## Components

### 1. Symfony AI Platform (symfony/ai-platform)

- Role: Low-level abstraction over AI providers.
- Provides: PlatformInterface, Model, Message, MessageBag.
- Responsibility: Sends requests to a provider and receives raw responses.
- Our use: The foundation layer. Business code does not call this directly.

Example:
- Platform sends the HTTP request to Gemini.
- It gets back the raw response.
- Your app code never calls Platform directly — the Agent does.



### 2. Symfony AI Agent (symfony/ai-agent)

- Role: High-level orchestrator.
- Provides: AgentInterface, tool registration, system prompts.
- Responsibility: Combines a Platform, a system prompt, and tools into a callable agent.
- Our use: The main entry point for asking questions to the model.

Example:
- You call the Agent with a question.
- Agent builds the prompt, selects tools, and calls Platform.
- Agent is what you inject as `ai.agent.default`.



### 3. Symfony AI Chat

- Role: Conversation session management.
- Responsibility: Maintains message history across turns.
- Our use: Short-lived context for follow-up questions starting Week 5.

Example:
- User asks "What about #43?" after asking about #42.
- Chat stores the previous messages so the follow-up has context.



### 4. Symfony AI Store (symfony/ai-store)

- Role: Vector storage and similarity retrieval.
- Provides: StoreInterface, InMemoryStore, PgVector store.
- Responsibility: Indexes embedded documents and retrieves nearest matches.
- Our use: RAG pipeline starts with InMemoryStore, migrates to pgvector later.

Example:
- You index 100 documents into the Store.
- User asks a question.
- Store returns the 3 most similar documents (nearest matches).



### 5. Symfony AI Bundle (symfony/ai-bundle)

- Role: Symfony FrameworkBundle integration.
- Responsibility: Wires Platform, Agent, Chat, and Store into the DI container via ai.yaml.
- Our use: Reads config/packages/ai.yaml and exposes services like ai.platform.gemini and ai.agent.default.

Example:
- You write config in `config/packages/ai.yaml`.
- Bundle auto-creates services.
- You inject `ai.agent.default` anywhere in your code.



### 6. Symfony MCP Bundle

- Role: Model Context Protocol integration.
- Responsibility: Consumes or exposes tools via MCP servers.
- Our use: Deferred to stretch goals in section 7.3. Not used in MVP.

Example:
- MCP lets external servers expose tools to the Agent.
- Not needed now — deferred to a later phase.



## Data Flow

1. Client sends request to Symfony Controller.
2. Controller calls Application Service for auth check.
3. Application Service calls Agent (`ai.agent.default`).
4. Agent calls Platform (Gemini) + Store (retrieval) + Tools (read-only services).
5. Response goes through Validation.
6. Validated response returns to Client.



## Boundaries (who owns what)

- Symfony AI Bundle owns config and DI wiring. Does not own business logic.
- Agent owns prompt and tool orchestration. Does not own authorization.
- Platform owns provider HTTP calls. Does not own response validation.
- Store owns vector retrieval. Does not own source content ownership.
- Tools own read-only domain calls. Do not own direct database access.
- Application Services own authorization and validation. Do not own model calls.


## Version Reference

- symfony/ai-platform: 0.14.1
- symfony/ai-agent: 0.14.1
- symfony/ai-bundle: 0.14.1
- symfony/ai-gemini-platform: 0.14.0
- symfony/ai-store: 0.14.0



