# SKILLMAP - SKILLED PERSONNEL LOCATOR PLATFORM
## Complete Project Specification (SRS v2.0)

---

## EXECUTIVE SUMMARY

Skillmap is a comprehensive web-based platform that connects customers with skilled professionals for various trade services. The platform facilitates job matching, quote management, real-time communication, and performance rating through an intuitive three-role dashboard system.

**Target Users:**
- **Admins** - Platform managers and verification controllers
- **Taskers** - Skilled professionals offering services
- **Users** - Customers seeking skilled personnel

---

## PROJECT OVERVIEW

### Purpose
To create a modern, user-friendly marketplace for skilled personnel that streamlines the process of finding, quoting, communicating, and rating trade professionals.

### Technology Stack
- **Framework**: Laravel 10.3.3 (PHP 8.1 required)
- **Database**: MySQL 5.7+ or MariaDB 10.6.12
- **Frontend**: Blade Templates + Alpine.js + Tailwind CSS
- **Maps**: OpenStreetMap with Leaflet.js
- **Real-time Communication**: Laravel WebSockets v4 + Redis
- **Image Processing**: Intervention Image v2.7
- **Authentication**: Laravel Breeze with email verification + phone OTP

---

## SYSTEM ARCHITECTURE

### Three-Dashboard Role System

#### 1. ADMIN DASHBOARD (/admin/dashboard)
**Responsibilities:**
- Verify and approve/reject pending tasker registrations
- Manage platform trades and specializations
- Manage supported languages
- Process tasker reports (warn, suspend, ban)
- Promote/demote tasker status
- View platform statistics
- Set commission fees

**Key Features:**
- Verification queue with tasker profiles
- Platform statistics and metrics
- Trade management interface
- Specialization configuration per trade
- Language administration
- Report management and action system

#### 2. TASKER DASHBOARD (/tasker/dashboard)
**Responsibilities:**
- Manage complete professional profile
- Set availability status and working hours
- Manage pricing (hourly/fixed rates)
- View and respond to job quotes
- Track ratings and reviews
- Communicate via in-app chat

**Key Features:**
- Profile management with photo, bio, credentials
- Trade and specialization selection
- Location setting via OpenStreetMap
- Dual pricing model: hourly rate OR fixed rate
- Negotiable pricing toggle
- Multi-language capability
- Availability toggle (Available/Busy/On Leave)
- Weekly working hours configuration
- Real-time notifications for new quotes
- Chat interface with file attachments
- Rating history and average rating display

#### 3. USER DASHBOARD (/user/dashboard)
**Responsibilities:**
- Search for qualified taskers by location/skill
- Post job requests with descriptions and budgets
- Receive and evaluate quotes
- Rate taskers after job completion
- Manage favorite taskers list
- Chat with assigned taskers

**Key Features:**
- Location-based tasker search
- Map interface with clustering
- Advanced filtering options
- Save favorite taskers
- Job request management
- Quote comparison and selection
- Rating and review submission
- Chat thread management

---

## AUTHENTICATION & USER MANAGEMENT

### Registration Flow

#### Admin Registration
- Email address
- Password (with strength requirements)
- Automatic role assignment
- Email verification required

#### Tasker Registration
**Step 1: Account Creation**
- Name, email, password
- Role selection as "Tasker"

**Step 2: Email Verification**
- Verification link sent to email
- Account locked until verified

**Step 3: Phone Verification (OTP)**
- Email-based OTP sent (not SMS)
- 6-digit code with 10-minute expiration
- Phone number marked as verified_at

**Step 4: Profile Setup**
- Select base trade (dropdown list)
- Choose skill level:
  - Common (basic skills, 10km radius)
  - Specialized (advanced skills, 50km radius)
  - Promoted (premium feature - shows dialog, not functional yet)
- If Specialized: multi-select specializations tied to selected trade
- Set location using OpenStreetMap (no Google Maps)
- Configure pricing:
  - Option 1: Hourly rate
  - Option 2: Fixed rate
  - Toggle: "Negotiable pricing"
- Select languages spoken (multi-select, admin-managed)
- Write professional bio

**Step 5: Admin Approval**
- Profile enters verification queue
- Admin reviews and approves/rejects
- Email notification sent with status
- Rejected profiles may resubmit

#### User Registration
- Name, email, password
- Role selection as "User"
- Email verification only
- Optional: Set location for search preferences

### Email Verification
- **Scope**: Required for ALL user types
- **Mechanism**: Email link with signed verification token
- **Access Control**: Verified-only users can access dashboards

### Phone OTP Verification
- **Scope**: Taskers only
- **Method**: Email-based (NOT SMS)
- **OTP Format**: 6-digit numeric code
- **Expiration**: 10 minutes
- **Storage**: Cached in Redis
- **Resend**: Available after expiration

---

## TASKER REGISTRATION DETAILS

### Trade Selection
- **Type**: Dropdown with admin-managed list
- **Examples**: Plumbing, Electrical, Carpentry, Painting, HVAC, Masonry, Landscaping, Roofing
- **Required**: Yes
- **Multi-select**: No (one primary trade)

### Skill Level / Service Type
- **Radio Buttons**: Common | Specialized | Promoted
- **Common**: Local area service, basic skills, 10km radius
- **Specialized**: Advanced expertise, 50km radius
- **Promoted**: Premium feature (placeholder UI only, not functional)

### Specializations
- **Visibility**: Shown only if "Specialized" selected
- **Type**: Multi-select checkboxes
- **Source**: Specializations tied to selected trade
- **Examples**: 
  - For Plumbing: Leak Repair, Installation, Bathroom Remodeling
  - For Electrical: Wiring Installation, Circuit Breaker Repair, Lighting

### Location Setting
- **Method**: OpenStreetMap with Leaflet.js
- **Options**: 
  - Geolocation (user allows browser location)
  - Manual pin placement on map
- **Storage**: Latitude (decimal 10,8), Longitude (decimal 10,8)
- **No Google Maps**: Explicitly prohibited

### Pricing Configuration
- **Hourly Rate**: Optional decimal field (e.g., $50/hour)
- **Fixed Rate**: Optional decimal field (e.g., $500 for job)
- **Negotiable Checkbox**: Toggle to allow price negotiation
- **Validation**: At least one rate type must be set

### Languages
- **Type**: Multi-select
- **Admin-Managed**: Yes
- **Example List**: English, Spanish, French, German, Mandarin, Japanese, Portuguese, Arabic
- **Storage**: Many-to-many relationship

### Availability
- **Status Toggle**: Available / Busy / On Leave
- **On Leave Effect**: Tasker hidden from all searches
- **Weekly Hours**: Optional start/end time for working hours
- **Real-time Toggle**: Can change status anytime

---

## ADMIN DASHBOARD FEATURES

### Tasker Verification System
- **Queue Display**: Pending taskers awaiting approval
- **Information Shown**:
  - Profile photo
  - Name, email, phone
  - Trade and specializations
  - Location with map
  - Pricing information
  - Bio and experience summary

**Actions:**
- **Approve**: Move to verified status, send approval email
- **Reject**: With optional reason message, send rejection email
- Can reapply after rejection

### Trade Management (CRUD)
- **Create**: Add new trade with name, description, icon
- **Read**: List all trades with specialization count
- **Update**: Modify trade details
- **Delete**: Remove trade (cascade delete specializations)

### Specialization Management
- **Per-Trade**: Specializations grouped by trade
- **Create**: Add specialization to selected trade
- **Read**: Display with trade association
- **Update**: Modify name/description
- **Delete**: Remove specialization

### Language Management
- **Create**: Add language with name and language code (e.g., 'en', 'es')
- **Read**: List all supported languages
- **Delete**: Remove language

### Report Queue Management
- **Display**: List of tasker reports with:
  - Reporter name
  - Reported tasker name
  - Reason for report
  - Description/evidence
  - Report date
  - Current status

**Admin Actions:**
- **Dismiss**: Close report without action
- **Warn**: Send warning email to tasker
- **Suspend 7 Days**: Tasker hidden from search for 7 days
- **Suspend 30 Days**: Tasker hidden from search for 30 days
- **Permanent Ban**: Tasker permanently banned, cannot log in
- **Record Action**: Timestamp and reason saved

**Suspension Effects:**
- Suspended taskers don't appear in search results
- Existing quotes can still be managed
- Email notification sent with action details

### Promoted Tasker Feature
- **UI Mockup**: Dialog showing "This feature is currently unavailable"
- **Functionality**: Non-functional placeholder
- **Future**: Can be monetized as premium feature

### Platform Statistics Dashboard
- **Metrics Displayed**:
  - Total verified taskers
  - Pending tasker approvals count
  - Active job requests count
  - Pending reports count
  - Total users registered
  - Monthly activity metrics

---

## USER SEARCH FUNCTIONALITY

### Search Interface
**Location Input:**
- Geolocation (allow/deny browser location access)
- Manual search by address/coordinates
- Map-based pin placement

**Filter Options:**
- Trade selection (dropdown)
- Specialization (dependent on trade)
- Minimum rating (star rating 1-5)
- Availability status filter
- Price range (min-max)
- Languages spoken
- Response time preference

### Search Results
**Radius Calculation (Haversine Formula):**
- Common skills: 10km radius
- Specialized skills: 50km radius
- No spatial indexes (use SQL Haversine)

**Display Modes:**
- Map view with clustered markers, radius circle
- List view with pagination
- Toggle between views

**Result Information:**
- Tasker photo and name
- Trade and specializations
- Average rating (stars) and review count
- Hourly/fixed rate
- "Negotiable" badge if applicable
- Distance from search location
- Languages spoken
- Availability status

**Actions from Results:**
- View tasker profile detail
- Add to favorites (heart icon)
- Request quote (send job request)

### Favorites Management
- **Save**: Click heart icon to save tasker
- **View Favorites**: List of saved taskers
- **Remove**: Click heart to unsave
- **Access**: From user dashboard

---

## QUOTE SYSTEM (Core Workflow)

### Step 1: Job Request Creation
**User Creates Job:**
- Title (e.g., "Fix leaking kitchen sink")
- Detailed description
- Trade selection (dropdown)
- Budget (decimal amount)
- Date needed (date/time picker)
- Location (address + coordinates)
- Attachments (optional photos)

**Workflow:**
- Job saved with status: "open"
- Notifications sent to qualified taskers within radius
- Job visible on tasker search results

### Step 2: Quote Submission
**Taskers Receive Notification:**
- "New job request in your area"
- Shows job title, description, location

**Tasker Sends Quote:**
- Price (decimal amount)
- Estimated hours/time to complete
- Message (e.g., "I can start Friday")
- Quote status: "pending"

**Multiple Quotes:**
- User can receive multiple quotes
- Taskers can't see other quotes yet
- Quotes listed with tasker info

### Step 3: Quote Acceptance
**User Accepts Quote:**
- Selects ONE quote to accept
- Quote status changes to "accepted"
- Other quotes status changes to "rejected"
- Job request status: "quote_accepted"

**Contact Information Revealed:**
- User sees tasker's full phone number
- Tasker sees user's full phone number
- Direct communication becomes possible

**Automatic Chat Creation:**
- System automatically creates chat thread
- Both parties can start messaging

### Step 4: Job Completion
**User Actions:**
- Confirm job completion
- Rate tasker (5 stars + written review)
- Job status: "completed"

**Notification Sent:**
- "Job marked as completed"
- Rating opportunity

---

## RATING SYSTEM

### Rating Submission
**User Rates Tasker:**
- 5-star rating (1-5 stars)
- Written review (minimum 10 characters)
- Optional: Tip/bonus

**Data Stored:**
- Rating (integer 1-5)
- Review text (full text)
- Timestamp
- Job reference
- Rater and rated user IDs

### Tasker Rates User (Optional)
- Same structure as user rating
- Not required

### Rating Display
- **Profile**: Average rating with star display
- **History**: All ratings on profile
- **Average Calculation**: (sum of all ratings) / count
- **Stored**: average_rating, rating_count on tasker_profiles

### Review Visibility
- Public on tasker profile
- Shows rater name and date
- Chronological order (newest first)

---

## AVAILABILITY MANAGEMENT

### Status Toggling
**States:**
- **Available**: Can receive job requests and quotes
- **Busy**: Can receive requests but may decline
- **On Leave**: Hidden from all searches

**Quick Toggle:**
- Dashboard quick action
- Profile settings page
- Real-time update

### Weekly Working Hours
- **Optional**: Not required but recommended
- **Format**: Start time and end time (e.g., 9:00 AM - 5:00 PM)
- **Display**: On profile for customer reference

### Suspension/Ban Effects
- **Suspended (7/30 days)**: Hidden from search, can't receive new requests
- **Banned**: Can't log in, profile hidden, cannot interact

---

## REPORTING SYSTEM

### Report Submission
**Any User Can Report Tasker:**
- Select reason (dropdown):
  - Fake profile
  - Spam/scam
  - Fraud
  - Harassment
  - Wrong skills
  - Other

**Report Details:**
- Description/evidence
- Attached evidence (optional)

**Report Status:**
- Submitted to admin queue
- Awaiting admin action

### Admin Actions
**Options:**
- Dismiss (no action)
- Warn (email warning, continues work)
- Suspend 7 days (hidden from search)
- Suspend 30 days (hidden from search)
- Permanent ban (can't log in)

**Recording:**
- Action date/time
- Reason for action
- Action timestamp
- Notification sent to tasker

### Appeal Process
- Not implemented in MVP
- Future feature

---

## NOTIFICATIONS

### Notification Types

**For Taskers:**
1. New job request in area
2. Quote accepted (contact revealed)
3. User rating received
4. Account verified/rejected
5. Suspend/warn/ban action
6. Chat message received

**For Users:**
1. Quote received from tasker
2. Quote rejected by tasker
3. Tasker rating received
4. Chat message received

**For Admins:**
1. New tasker pending verification
2. Report submitted
3. System alerts

### Notification Channels
- **In-App**: Database notifications, visible in notification bell
- **Email**: Sent to email address
- **Real-Time**: WebSocket for chat messages

**Email Configuration:**
- Log driver (emails to storage/logs)
- User can opt-out of email notifications
- Notification preferences in settings

---

## REAL-TIME CHAT

### Chat Thread Creation
- Automatic upon quote acceptance
- Linked to job request
- Includes user and tasker

### Chat Features
- **Text Messages**: Unlimited length
- **File Attachments**: Max 5MB per file
- **Image Support**: Compressed via Intervention Image v2.7
- **Emoji**: Full emoji support
- **Read Receipts**: Show when message read
- **Typing Indicators**: "User is typing..."

### Chat Archiving
- **Automatic**: 30 days after job completion
- **Archived Status**: Chat read-only, can view history
- **Admin Access**: Admins CANNOT access private chats

### Technology
- Laravel WebSockets v4
- Redis for pub/sub
- Laravel Echo for real-time updates
- Private channels per chat thread

---

## MAP & LOCATION

### Map Technology
- **Library**: Leaflet.js
- **Tiles**: OpenStreetMap (NO Google Maps fallback)
- **Features**:
  - Marker clustering for dense areas
  - Radius circle display (10km or 50km)
  - Directions button using OSRM (Open Route Service Machine)
  - Manual pin placement for location setting

### Location Data
- **Storage**: Decimal format (10,8 precision)
  - Latitude: decimal(10,8)
  - Longitude: decimal(10,8)
- **Accuracy**: ~1.1mm at equator
- **Update**: Stored on tasker profile and job requests

### Search Radius
- **Common Skills**: 10km radius around search point
- **Specialized Skills**: 50km radius around search point
- **Calculation**: Haversine formula in SQL

---

## DATABASE SCHEMA

### Core Tables

**users**
- id, name, email, password (hashed)
- role (enum: admin, tasker, user)
- latitude, longitude (decimal)
- phone, phone_verified_at (nullable timestamp)
- email_verified_at (timestamp)
- verification_status (enum: pending, verified, rejected)
- suspension_status (enum: none, warning, suspended, banned)
- timestamps

**trades**
- id, name, description, icon
- timestamps

**specializations**
- id, trade_id (FK), name, description
- timestamps

**languages**
- id, name, code (e.g., 'en')
- timestamps

**tasker_profiles**
- id, user_id (unique FK), trade_id (FK)
- hourly_rate, fixed_rate (decimal, nullable)
- price_negotiable (boolean)
- bio (text, nullable)
- rating_count (int), average_rating (decimal)
- is_promoted (boolean)
- timestamps

**tasker_specializations** (Pivot)
- id, user_id (FK), specialization_id (FK)
- unique(user_id, specialization_id)

**tasker_languages** (Pivot)
- id, user_id (FK), language_id (FK)
- unique(user_id, language_id)

**tasker_availability**
- id, user_id (unique FK)
- status (enum: available, busy, on_leave)
- weekly_hours_start, weekly_hours_end (nullable time)
- timestamps

**job_requests**
- id, user_id (FK), trade_id (FK)
- title, description (text)
- budget (decimal), date_needed (datetime)
- status (enum: open, quote_accepted, completed)
- location, latitude, longitude
- timestamps

**quotes**
- id, job_request_id (FK), user_id (tasker FK)
- price (decimal), estimated_hours (decimal, nullable)
- message (text, nullable)
- status (enum: pending, accepted, rejected)
- timestamps

**ratings**
- id, job_request_id (FK)
- rater_id (user FK), rated_user_id (user FK)
- rating (int 1-5), review (text)
- timestamps

**user_favorites** (Pivot)
- id, user_id (FK), tasker_id (user FK)
- unique(user_id, tasker_id)

**tasker_reports**
- id, reported_tasker_id (user FK), reporter_id (user FK)
- reason (enum: fake, spam, fraud, harassment, wrong_skills, other)
- description (text, nullable)
- admin_action (enum: dismissed, warned, suspended_7d, suspended_30d, banned)
- action_date (nullable timestamp)
- timestamps

**notifications**
- id, user_id (FK), type, notifiable_id, notifiable_type
- data (json), read_at (nullable)
- created_at

**chat_threads**
- id, job_request_id (FK), user_id (FK), tasker_id (FK)
- is_archived (boolean)
- archived_at (nullable timestamp)
- timestamps

**chat_messages**
- id, chat_thread_id (FK)
- sender_id (user FK), receiver_id (user FK)
- message (text), attachment (nullable string path)
- is_read (boolean), read_at (nullable timestamp)
- timestamps

---

## SECURITY REQUIREMENTS

### XSS Prevention
- All Blade templates use escaped output: {{ $variable }}
- Database values escaped on display
- Input sanitization on forms

### SQL Injection Prevention
- Parameterized queries throughout
- Eloquent ORM for all database operations
- No raw queries without parameter binding

### CSRF Protection
- Laravel CSRF token on all forms
- Middleware protection on POST/PUT/DELETE routes

### Password Security
- Hashed using bcrypt (Laravel default)
- Password reset via email token
- Password confirmation on registration

### Session Security
- Secure session cookies
- HTTPS enforced in production
- Session timeout: 120 minutes (configurable)

### File Upload Security
- Max 5MB for chat images
- Whitelist file types (image only)
- Store outside public directory
- Serve through controller with access checks

---

## ERROR HANDLING & VALIDATION

### Form Validation
- Server-side validation on all forms
- Client-side feedback (Alpine.js)
- Error messages displayed inline
- Required field indicators

### Exception Handling
- 404 for not found resources
- 403 for unauthorized access
- 419 for expired CSRF tokens
- 500 for server errors
- 503 for maintenance mode

### Logging
- Laravel default logging (storage/logs)
- Error stack traces in development
- Sanitized logs in production

---

## PERFORMANCE OPTIMIZATION

### Database
- Indexed foreign keys
- Indexed status fields
- Query optimization via Eloquent relationships
- Pagination on list views (15 per page)

### Caching
- Redis for session storage
- Query result caching where appropriate
- Route caching in production

### Frontend
- CSS/JS minification via Vite
- Lazy loading for images
- Alpine.js for lightweight interactivity

---

## FUTURE ENHANCEMENTS

### Phase 2 Features
1. Payment processing (Stripe/PayPal)
2. Commission tracking
3. Dispute resolution system
4. Advanced analytics dashboard
5. Task scheduling and reminders
6. Integration with Google Calendar
7. SMS notifications (Twilio)
8. Mobile app (React Native/Flutter)
9. AI-powered matching algorithm
10. Video call support

### Scalability
- Database replication
- Load balancing
- Caching layer (Redis cluster)
- CDN for static assets
- Horizontal scaling architecture

---

## PROJECT DELIVERABLES

✅ **Complete Application Includes:**
1. All 15 database migrations
2. All 11 Eloquent models with relationships
3. All controllers (Admin, Tasker, User, Auth, Chat, Quote)
4. Role-based middleware registered in Kernel.php
5. All Blade views (organized by role)
6. Alpine.js components for interactivity
7. Leaflet.js + OpenStreetMap integration
8. Laravel WebSockets v4 configuration
9. 6 notification classes (queued)
10. 3 WebSocket events (NewChatMessage, TypingIndicator, MessageRead)
11. Complete route configuration
12. Comprehensive seeders with test data
13. Feature and unit tests
14. Production-ready configuration

---

## TECHNICAL SPECIFICATIONS

### System Requirements
- **PHP**: 8.1.x or higher
- **Laravel**: 10.3.3
- **Database**: MySQL 5.7+ or MariaDB 10.6.12+
- **Redis**: For caching and WebSockets
- **Node.js**: For frontend asset compilation
- **Composer**: For PHP dependency management
- **npm**: For JavaScript package management

### Dependencies (Key Packages)
- Laravel Framework 10.3.3
- Laravel Sanctum 3.3+ (API authentication)
- Laravel Tinker 2.8+ (CLI helper)
- Intervention Image 2.7 (image processing)
- Laravel WebSockets 4.x (real-time)
- Laravel Breeze (scaffolding - optional)

### Deployment
- Follows Laravel deployment best practices
- Environment-based configuration
- Service provider loading
- Command scheduling support
- Job queue workers
- Error monitoring ready

---

## TIMELINE & MILESTONES

**Estimated Development:**
- Phase 1 (Core): 4-6 weeks
- Phase 2 (Payments): 2-3 weeks
- Phase 3 (Analytics): 1-2 weeks
- Testing & QA: 2-3 weeks
- Deployment: 1 week

**Total**: 10-15 weeks for complete production release

---

## CONCLUSION

Skillmap is a comprehensive, feature-rich platform designed to modernize how customers and skilled professionals connect. The architecture is scalable, secure, and built on proven Laravel patterns. The three-role dashboard system provides clear separation of concerns with intuitive user experiences for each actor.

**Key Differentiators:**
- OpenStreetMap (no proprietary vendor lock-in)
- Email-based OTP (SMS-independent)
- Comprehensive admin controls
- Real-time communication
- Location-intelligent search
- Transparent rating system

---

**Document Version**: 2.0
**Last Updated**: May 2026
**Status**: Complete Specification
**Ready for**: Development & Deployment
