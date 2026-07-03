# Step 8 Remaining TODO

This checklist captures the remaining production hardening work after the Step 8 foundation implementation.

## AI Provider And Governance
- [ ] Add provider failover chain (OpenAI primary, null fallback, optional secondary provider).
- [ ] Add rate-limit and retry policy per tenant for AI generation queues.
- [ ] Add redaction layer for PII/secret filtering before prompts are sent externally.
- [ ] Add configurable moderation/safety checks before persisting generated outputs.

## Content Extraction Pipeline
- [ ] Add robust URL fetch + HTML readability extraction for source_type=url.
- [ ] Add DOCX/PPTX extraction adapters.
- [ ] Add OCR pipeline for image-only PDFs.
- [ ] Add extraction chunking and deduplication for very large files.

## Question Bank Quality
- [ ] Add question validation ruleset (minimum stem quality, answer consistency, duplicate detection).
- [ ] Add review workflow with draft/reviewed/approved lifecycle and reviewer attribution.
- [ ] Add bulk publish/archive endpoints and audit logs.

## Security And Compliance
- [ ] Add antivirus/malware scanning on uploaded files before extraction.
- [ ] Add signed-download and object-level authorization for source files.
- [ ] Add retention policy jobs for raw files and generated outputs.

## Observability And Operations
- [ ] Add structured metrics: queue latency, extraction duration, generation duration, failure rates.
- [ ] Add dead-letter queue handling and alerting for failed extraction/generation jobs.
- [ ] Add tenant-scoped dashboard for pipeline health and throughput.

## API And Frontend UX
- [ ] Expand OpenAPI with request/response schemas and error contracts for all Step 8 endpoints.
- [ ] Add create forms and action workflows in React pages (currently list-focused foundations).
- [ ] Add pagination controls and search/filter UI wired to API query params.
- [ ] Add optimistic updates and polling/websocket updates for async processing status.

## Testing
- [ ] Add integration tests for successful PDF extraction path with fixture PDFs.
- [ ] Add contract tests for OpenAI adapter response normalization and failure paths.
- [ ] Add event/listener tests for AI generation -> QuestionBank persistence workflow.
- [ ] Add multi-tenant isolation tests for cross-tenant access denial across all Step 8 endpoints.
