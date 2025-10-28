# Hear Me Out

A Laravel 12 web application where users can create and share their "crushes" with the community. Think Tinder meets opinion sharing!

## Features

### Authentication
- Email/password login
- Google OAuth integration via Laravel Socialite
- Secure session management

### Pages

#### 1. Homepage (/)
- Displays a random crush to visitors
- Tinder-like interface with large images and vote buttons
- Four voting options:
  - "Oui" (Yes)
  - "Non" (No)
  - "Non, taré" (No, crazy)
  - "Taré mais oui" (Crazy but yes)
- After voting, displays statistics with percentages
- Share functionality
- Report button for inappropriate content
- Comment section with like/dislike reactions

#### 2. Login Page (/login)
- Email/password authentication
- Google OAuth "Sign in with Google" button
- Redirects to account page after successful login

#### 3. Account Page (/account)
- Each user can create exactly ONE crush
- CRUD operations for crush:
  - Create: Add title, text, and optional image
  - Read: View your crush details and statistics
  - Update: Modify text (keeps stats) or title/image (resets stats)
  - Delete: Remove your crush completely
- Real-time statistics display
- View all comments on your crush with reactions count

#### 4. Logout (/logout)
- Ends user session and redirects to homepage

## Technical Stack

- **Framework**: Laravel 12.35.1
- **PHP**: 8.3.6
- **Database**: SQLite (easily switchable to MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze-style + Socialite
- **Frontend**: Blade templates with vanilla CSS (no framework)
- **File Storage**: Local storage with symlink

## Installation

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & NPM (for asset compilation if needed)

### Setup

1. Clone the repository
```bash
git clone https://github.com/ovalletTheSecond/wop_hearmeout.git
cd wop_hearmeout
```

2. Install dependencies
```bash
composer install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure Google OAuth (optional)
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project or select existing
   - Enable Google+ API
   - Create OAuth 2.0 credentials
   - Add authorized redirect URI: `http://your-domain.test/login/google/callback`
   - Update `.env` with credentials:
   ```
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT_URI=http://your-domain.test/login/google/callback
   ```

5. Run migrations
```bash
php artisan migrate
```

6. Create storage symlink
```bash
php artisan storage:link
```

7. Start development server
```bash
php artisan serve
```

8. Visit `http://localhost:8000` in your browser

## Database Schema

### Users
- id, name, email, password, google_id, timestamps

### Crushes
- id, user_id, title, text, image_path, stats_version, timestamps
- Each user can have only ONE crush (enforced in controller)

### Votes
- id, crush_id, vote_type, ip_address, session_id, stats_version, timestamps
- Tracks votes with stats version for reset functionality

### Comments
- id, crush_id, user_id, text, timestamps
- User comments on crushes

### Comment Reactions
- id, comment_id, user_id, type (like/dislike), timestamps
- One reaction per user per comment

### Reports
- id, crush_id, ip_address, reason, timestamps
- Content moderation system

## Key Features Implementation

### Stats Reset Logic
When a user updates their crush:
- **Text only change**: Statistics are preserved
- **Title or image change**: Stats version increments, effectively resetting vote counts

### Vote Tracking
- Uses session storage to prevent duplicate votes per crush
- Stores IP address and session ID for analytics
- Ties votes to stats version for accurate counting

### Security
- Authentication required for account and comment routes
- CSRF protection on all forms
- Input validation on all user inputs
- File upload validation (image type, size limit)
- SQL injection protection via Eloquent ORM

## Contributing

This is a personal project. Feel free to fork and customize for your needs!

## License

MIT License - see LICENSE file for details

## Credits

Developed as part of a Laravel learning project. Built with ❤️ using Laravel 12.
