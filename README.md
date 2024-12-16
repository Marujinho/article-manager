# Article Manager

**Article Manager** is an application that manages articles from different sources.

---

## 🚀 Getting Started

Follow these steps to set up and run the application:

### Prerequisites

1. **Docker**: Ensure Docker is installed on your machine.  
   - [Install Docker](https://docs.docker.com/get-docker/)

2. Prepare a `.env` file with the required API keys and your database credentials.

---

### Installation

1. Clone this repository:
   ```bash
   git clone <repository_url>
   cd article-manager

2. Create a .env file and configure the following variables:
    # Example .env file
    DATABASE_USER=your_database_user
    DATABASE_PASSWORD=your_database_password
    API_KEY=your_api_key

3. Start the application using Docker Compose:
docker-compose up -d


🎉 Done!

The application should now be running at:
http://localhost:8000

---------------------------------------------------------


### Observations:
1. **The application seeds itself automatically on the first run.**
2. **A scheduled job runs daily to continue seeding the database (for demonstration purposes only; this is not required for testing functionality).Development Mode:**
3. **The database is destroyed and rebuilt every time the containers are restarted.**
4. **To make API requests, you need a personal_access_token. Generate this token by registering at the /register endpoint. Use the token in your request headers: Authorization: Bearer {token}**




