# Symfony AI Component Map

## Purpose
Document the responsibilities of each Symfony AI component and how they interact in the CTI Assistant proof of concept.

## Component Overview

### Platform
- **Responsibility**: Abstraction for connecting to AI providers (Gemini, OpenAI, Ollama, etc.)
- **What it does**: Routes invoke() calls to the correct provider, handles authentication, model resolution
- **In this project**: Configured with Gemini (ai.platform.gemini)
- **Key concept**: A Platform can route to multiple providers via model catalogs

### Agent
- **Responsibility**: Wraps a Platform and manages conversation flow, system prompts, and tool calling
- **What it does**: Accepts MessageBag, calls the platform, handles tool execution loop
- **In this project**: default agent configured with gemini-3.5-flash-lite and a system prompt
- **Key concept**: Agents sit on top of Platform and optionally Store

### Chat
- **Responsibility**: Persistent conversation context management
- **What it does**: Stores message history, allows resuming conversations
- **Status in this project**: Not yet configured (deferred to later week)
- **Dependency**: Requires a message store bridge (Cache, Doctrine, Pogocache, etc.)

### Store
- **Responsibility**: Vector storage and retrieval for RAG
- **What it does**: Indexes documents, performs similarity search, returns relevant chunks
- **Status in this project**: Not yet configured (deferred to Week 4)
- **Planned backend**: PostgreSQL pgvector
- **Options**: InMemoryStore, SQLite, PostgreSQL, Redis, ChromaDB, etc.

### AI Bundle
- **Responsibility**: Symfony integration glue
- **What it does**: Registers services, wires configuration, provides Profiler integration
- **In this project**: Installed via symfony/ai-bundle
- **Key benefit**: Auto-configures agents and platforms from ai.yaml

### MCP Bundle
- **Responsibility**: Model Context Protocol integration
- **What it does**: Exposes Symfony capabilities as MCP tools, or consumes external MCP servers
- **Status in this project**: Not yet explored (stretch objective)
- **Note**: Still experimental

## Interaction Flow (Current State)

Client Request
    -> Controller (AiTestController)
        -> AgentInterface (injected as ai.agent.default)
            -> Platform (ai.platform.gemini)
                -> Gemini API
                    -> Response

## Interaction Flow (Target State)

Client Request
    -> Controller
        -> Agent
            -> Store (RAG retrieval) + Tools (CTI services)
                -> Platform
                    -> Provider (Gemini or Ollama)
                        -> Validated Structured Response

## Experimental Warning

All Symfony AI components are experimental and not covered by Symfony's Backward Compatibility Promise. APIs may change between releases.

## Versions Used

| Package | Version |
|---------|---------|
| symfony/ai-bundle | 0.14.1 |
| symfony/ai-agent | 0.14.1 |
| symfony/ai-gemini-platform | 0.14.0 |
| symfony/ai-platform | 0.14.1 |
| Symfony Framework | 7.4.20 |
| PHP | 8.2.12 |

## Configuration Reference

config/packages/ai.yaml:

ai:
    platform:
        gemini:
            api_key: '%env(GEMINI_API_KEY)%'

    agent:
        default:
            platform: 'ai.platform.gemini'
            model: 'gemini-3.5-flash-lite'
            prompt: 'You are a helpful assistant.'

.env.local:

GEMINI_API_KEY=your-actual-key-here