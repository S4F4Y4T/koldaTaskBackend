# Backend Port Change Summary

## Overview
Changed the backend application port from **6000** to **8080** for better compatibility and safety.

## Port Selection Rationale
- **Port 8080** is a standard alternative HTTP port
- Commonly used for web applications and development servers
- Less likely to conflict with other services
- Well-documented and widely supported

## Files Updated

### 1. Docker Configuration
**File:** `docker-compose.yml`
- **Line 18:** Changed nginx port mapping from `6000:80` to `8080:80`
- **Impact:** The application now runs on port 8080 on the host machine

### 2. Environment Configuration
**File:** `.env.example`
- **Line 6:** Updated `APP_URL` from `http://127.0.0.1:6000` to `http://127.0.0.1:8080`
- **Action Required:** If you have a `.env` file, manually update it with the same change

### 3. Postman Collection
**File:** `documentation/KoldaTech Backend API.postman_collection.json`
- **Line 681:** Updated `base_url` variable from `http://localhost/api/v1` to `http://127.0.0.1:8080/api/v1`
- **Impact:** All API requests in Postman will now use the correct port

### 4. Documentation Files

#### README.md
**File:** `documentation/README.md`
- **Lines 43, 45:** Updated API and Health Check URLs to use port 8080
- **Line 147:** Updated Docker services description to reflect port 8080

#### TECHNICAL_DOCUMENTATION.md
**File:** `documentation/TECHNICAL_DOCUMENTATION.md`
- **Line 489:** Updated APP_URL example to use port 8080
- **Line 552:** Updated API Base URL to use port 8080
- **Line 562:** Updated health check curl command to use port 8080
- **Line 604:** Updated Docker architecture diagram to show port 8080
- **Line 625:** Updated nginx container ports documentation to 8080:80
- **Line 687:** Updated troubleshooting command to check port 8080

## New Access Points

After restarting the Docker containers, access the application at:

- **API Base URL:** http://127.0.0.1:8080/api/v1
- **Health Check:** http://127.0.0.1:8080/api/v1/health
- **phpMyAdmin:** http://127.0.0.1:6080 (unchanged)

## Required Actions

### 1. Update Your .env File
If you have a `.env` file (not tracked in git), update it manually:
```bash
# Change this line in your .env file:
APP_URL=http://127.0.0.1:8080
```

### 2. Restart Docker Containers
To apply the port changes, restart your Docker containers:
```bash
docker-compose down
docker-compose up -d
```

### 3. Update Postman Collection
If you're using Postman:
- Re-import the updated collection from `documentation/KoldaTech Backend API.postman_collection.json`
- Or manually update the `base_url` environment variable to `http://127.0.0.1:8080/api/v1`

### 4. Update Any Bookmarks or Scripts
If you have any:
- Browser bookmarks pointing to the old port
- Scripts or automation tools using the API
- Frontend applications connecting to the backend

Update them to use port **8080** instead of **6000**.

## Verification

After restarting the containers, verify the change:

```bash
# Check if the port is listening
lsof -i :8080

# Test the health endpoint
curl http://127.0.0.1:8080/api/v1/health

# Expected response:
# {
#   "status": "success",
#   "message": "Healthy",
#   "data": {
#     "timestamp": "2024-12-02T18:15:35.000000Z"
#   }
# }
```

## Rollback Instructions

If you need to revert to port 6000:

1. In `docker-compose.yml`, change line 18 back to: `- "6000:80"`
2. In `.env.example` and `.env`, change `APP_URL` back to: `http://127.0.0.1:6000`
3. In Postman collection, change `base_url` back to: `http://localhost/api/v1` or `http://127.0.0.1:6000/api/v1`
4. Restart Docker containers: `docker-compose down && docker-compose up -d`

---

**Date:** 2025-12-03
**Changed By:** Automated port migration
**Reason:** Moving to a safer, more standard port for web applications
