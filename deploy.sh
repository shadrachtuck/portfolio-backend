#!/bin/bash
# Quick deployment script for portfolio-backend
# Usage: ./deploy.sh "Commit message"

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get commit message from argument or use default
COMMIT_MSG="${1:-Update backend}"

echo -e "${YELLOW}Starting deployment...${NC}"

# Navigate to backend directory
cd "/Users/shadrachtuck/Local Sites/portfolio/portfolio-backend"

# Check if we're on the right branch
CURRENT_BRANCH=$(git branch --show-current)
echo -e "${YELLOW}Current branch: ${CURRENT_BRANCH}${NC}"

# Show what will be committed
echo -e "${YELLOW}Changes to be committed:${NC}"
git status --short

# Ask for confirmation
read -p "Continue with deployment? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Deployment cancelled."
    exit 1
fi

# Add all changes (excluding .md files if you want)
echo -e "${YELLOW}Staging changes...${NC}"
git add -A

# Commit
echo -e "${YELLOW}Committing changes...${NC}"
git commit -m "$COMMIT_MSG" || echo "No changes to commit"

# Push to GitHub
echo -e "${YELLOW}Pushing to GitHub...${NC}"
REMOTE_NAME=$(git remote | head -1)
git push "$REMOTE_NAME" "$CURRENT_BRANCH"

# Deploy to production server
echo -e "${YELLOW}Deploying to production server...${NC}"
ssh shadrach@backend.shadrach-tuck.dev "cd /var/www/html/portfolio-backend && git pull $REMOTE_NAME $CURRENT_BRANCH && echo 'Deployment complete on server!'"

echo -e "${GREEN}Deployment complete!${NC}"
