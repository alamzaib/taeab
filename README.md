# Job Portal UAE - Laravel Multi-Authentication System

A comprehensive job portal application built with Laravel featuring multi-authentication system for Agents, Job Seekers, Companies, and Admins.

## Features

- **Multi-Authentication System**: Separate authentication for 4 user types:
  - **Agents**: Can post jobs and manage applications
  - **Job Seekers**: Can browse and apply for jobs
  - **Companies**: Can post jobs and manage their company profile
  - **Admins**: Full system administration access

- **Separate Dashboards**: Each user type has their own dedicated dashboard
- **Modern UI**: Beautiful interface with custom color scheme (#235181)
- **Job Portal**: Designed specifically for the UAE job market
- **Admin Settings Panel**: Comprehensive settings management for logos, SEO, analytics, and contact information
- **Job Module**: Admin, company, and agent job creation with bulk import
- **Static Pages**: Admin can create pages like About, Terms, Privacy with custom meta tags
- **LinkedIn Login**: Seekers can sign in/sign up using their LinkedIn account
- **Seeker Document Manager**: Upload multiple CVs and cover letters, set defaults, reuse them when applying

## Installation

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Configuration**
   Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

If you added the project before this update, run the migrations again to create the `jobs` table:
```bash
php artisan migrate
```

5. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```
   This is required for logo and favicon uploads in the admin settings.

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

7. **Run Latest Migrations**
   ```bash
   php artisan migrate
   ```
   This will create the jobs, pages, job documents, and job applications tables used by the new features.

### LinkedIn Social Login Setup (Seeker Accounts)

1. Install Socialite if you haven't yet:
   ```bash
   composer install
   ```
   (Already included in `composer.json`; run `composer update` if needed.)
2. Create a LinkedIn OAuth app and set the redirect URI to `https://your-domain.com/seeker/login/linkedin/callback`.
3. Add the credentials to `.env`:
   ```env
   LINKEDIN_CLIENT_ID=your-linkedin-app-id
   LINKEDIN_CLIENT_SECRET=your-linkedin-app-secret
   LINKEDIN_REDIRECT_URI=https://your-domain.com/seeker/login/linkedin/callback
   ```

## User Types & Routes

### Agent
- Login: `/agent/login`
- Register: `/agent/register`
- Dashboard: `/agent/dashboard`

### Job Seeker
- Login: `/seeker/login`
- Register: `/seeker/register`
- Dashboard: `/seeker/dashboard`

### Company
- Login: `/company/login`
- Register: `/company/register`
- Dashboard: `/company/dashboard`

### Admin
- Login: `/admin/login`
- Dashboard: `/admin/dashboard`
- Settings: `/admin/settings`

## Database Structure

The application uses separate tables for each user type:
- `agents` - Agent users
- `seekers` - Job seeker users
- `companies` - Company users
- `admins` - Admin users
- `settings` - Application settings (logos, SEO, contact info, etc.)

## Test Accounts

After running migrations and seeders, you can use these test accounts:

- **Agent**: `agent@test.com` / `Test@1234`
- **Job Seeker**: `seeker@test.com` / `Test@1234`
- **Company**: `company@test.com` / `Test@1234`
- **Admin**: `admin@test.com` / `Test@1234`

To create test accounts, run:
```bash
php artisan db:seed --class=TestUsersSeeder
```

Or run all seeders:
```bash
php artisan db:seed
```

## Homepage Features

- **Header**: Logo, navigation menu (Home, Jobs, Companies, Account dropdown)
- **Hero Section**: Search bar with job title and location filters
- **Features Section**: Highlights key benefits
- **Job Categories**: Popular job categories with quick links
- **Footer**: Logo, quick links, employer links, social media icons, contact information, and copyright

## Job Module

### Admin
- Manage all jobs from `/admin/jobs`
- Assign jobs to any company and optionally to an agent
- Bulk import jobs from CSV
- Publish, draft, or close jobs

#### CSV Import Format
Upload a `.csv` file with the following headers (lowercase, in order):
```
title,company_email,location,job_type,status,salary_min,salary_max,short_description,description
```
- `company_email` must match an existing company account  
- `status` must be `draft`, `published`, or `closed`

Import UI is available at `/admin/jobs/import`.

### Company Dashboard
- Companies can post, edit, and delete their own jobs at `/company/jobs`
- Quick links on the dashboard for posting and managing jobs
- Stats for total and active jobs

### Agent Dashboard
- Agents can post jobs on behalf of any company at `/agent/jobs`
- Each job is tied to the agent account for tracking
- Dashboard shows job totals and quick job management links

## Seeker Documents & Applications

- Manage resumes and cover letters from the seeker dashboard at `/seeker/documents`
- Upload PDF, DOC, or DOCX files (up to 5 MB), keep multiple versions, and mark one default per type
- Job applications automatically attach the default resume/cover-letter if available
- Applying from `/jobs/{slug}` prompts unauthenticated users to log in with SweetAlert and prevents duplicate submissions

## Static Pages

- Manage page content at `/admin/pages`
- Fields include title, slug, status (draft/published), full content, meta title, and meta description
- Pages can be published for seekers via `/pages/{slug}` (e.g. `/pages/about-us`)
- Useful for About, Terms & Conditions, Privacy Policy, FAQ, etc.

## Admin Settings

The admin panel includes comprehensive settings management:

### Logo & Icons
- Application Logo (header)
- Footer Logo
- Favicon

### SEO Settings
- Homepage Meta Title
- Meta Description
- Meta Keywords

### Analytics & Custom Code
- Google Analytics Code
- Custom CSS (added to `<head>`)
- Custom JavaScript (added to `<head>`)

### Contact Information
- Phone Number
- Official Email
- Address
- City
- Country
- Latitude & Longitude (for maps integration)

All settings are dynamically applied throughout the application.

## Color Scheme

The application uses a custom color scheme:
- Primary Color: `#235181`
- Hover Color: `#1a3d63`

## Technology Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Blade Templates
- Custom CSS

## Next Steps

1. Add job posting functionality
2. Implement application management and seeker job applications
3. Add resume upload for seekers
4. Implement advanced search and filtering
5. Add job categories and tags
6. Integrate maps using latitude/longitude from settings

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
