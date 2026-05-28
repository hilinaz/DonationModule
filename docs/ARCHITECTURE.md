# Donor & Fundraising Module Architecture

## Stack
- Backend: Laravel 12+ compatible design (current project uses Laravel 13)
- Admin Panel: Filament 5 (pending PHP `intl` extension enablement)
- Frontend: Tailwind CSS 4 + Vite
- Runtime: PHP 8.2+ (current local runtime is newer)
- Database: PostgreSQL

## Bounded Contexts
- Donor Management
- Campaign Management
- Donation Management
- Communication & Engagement
- Reporting & Analytics
- Workflow Automation

## High-level Layers
- Presentation Layer
  - Filament admin pages/resources for ERP users
  - Donor portal pages and public donation screens
- Application Layer
  - Service classes (`app/Services`) orchestrating workflows
  - Jobs/events/listeners for async operations
- Domain Layer
  - Eloquent models and relationships for donors, campaigns, donations, pledges, recurring plans, and communication logs
- Infrastructure Layer
  - PostgreSQL persistence
  - Queue-backed jobs for email, SMS, receipts, automation triggers
  - External integrations (payments, SMS, exchange rates)

## Core Data Model (Implemented Foundation)
- `donors`: donor identity, segmentation, lifecycle, engagement, and contact fields
- `campaigns`: campaign goals, schedule, status, and visibility
- `donations`: one-time/recurring/pledge/in-kind transaction records with multi-currency support
- `recurring_donations`: recurring donation plans and renewal schedule
- `pledges`: pledge commitments, due dates, and fulfillment tracking
- `communication_logs`: message history across channels
- `donor_preferences`: communication opt-ins and campaign interests

## Role Model (Planned)
- Admin
- Fundraising Manager
- Finance
- Marketing
- Donor
- Auditor

## Key Flows
1. Donor registration and segmentation
2. Campaign setup and activation
3. Donation capture and status update
4. Receipt generation and communication logging
5. Reporting and KPI visualization
6. Automation triggers for pledges, lifecycle changes, and large donations

## Delivery Plan
- Phase 1: Core donor/donation foundation (in progress)
- Phase 2: Campaign workflows and baseline reports
- Phase 3: Payment integration and donor portal
- Phase 4: Advanced analytics, automation, and external integration APIs
