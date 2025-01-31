#!/bin/bash

# ==============================================================================
# Sync Changes From a Server to the Central Remote Repository
#
# This script is intended to be run on a production or staging server to
# synchronize content changes made via the Kirby panel back to the central
# repository.
#
# In most cases, running this script as a cron job will be a simple and
# efficient way of keeping your repository up to date with content changes made
# by website editors.
#
# Example cronjob (that syncs once every hour):
# 0 * * * * /path/to/website-directory/utilities/sync/sync-server-to-central-repo.sh >> /path/to/logs/cron-jobs/log_sync-server-to-central-repo.log 2>&1
# ==============================================================================

# ------------------------------------------------------------------------------
# Configuration
# ------------------------------------------------------------------------------

# Define the directory of your repository
REPO_DIR="/path/to/repository"

# Define the branch to sync
#
# Usually only one branch is used on a production or staging server anyway, but
# we want to be sure, just in case.
SYNC_BRANCH="main"

# Name the server
#
# This information is used in commit messages. Usually something like “staging
# server” or “production server” will do.
SERVER_NAME="production server"

# Define the log file to store information about merge conflicts.
MERGE_CONFLICTS_LOG="/path/to/logs/cron-jobs/sync-server-to-central-repo-merge-confligts.log"

# ------------------------------------------------------------------------------
# Main script
# ------------------------------------------------------------------------------

# Navigate to the repository directory
cd "${REPO_DIR}" || {
  echo "Could not find directory: ${REPO_DIR}" >&2
  exit 1
}

# Switch to the branch to sync
git checkout ${SYNC_BRANCH}

# Check the stash count before stashing
STASH_COUNT_BEFORE=$(git stash list | wc -l)

# Stash any untracked files and changes
git stash --include-untracked

# Check the stash count after stashing
STASH_COUNT_AFTER=$(git stash list | wc -l)

# Pull the latest changes from the main branch of the central repository
git pull origin ${SYNC_BRANCH} --no-edit

# Apply stashed changes only if something was stashed
if [ "${STASH_COUNT_BEFORE}" -lt "${STASH_COUNT_AFTER}" ]; then
  if git stash pop; then
    echo "Stashed changes applied successfully."
  else
    echo "Merge conflict detected at $(date)" >>"${MERGE_CONFLICTS_LOG}"
    echo "Merge conflict detected. Aborting script." >&2
    exit 1
  fi
else
  echo "No changes stashed, nothing to apply."
fi

# Check if there are any changes (including untracked files)
if git status --porcelain | grep -qE '^\s*[AMD\?\?]'; then
  echo "Local changes detected. Preparing to commit and push."

  # Add all changes to the staging area, including new files
  git add .

  # Commit the changes with a generic message. You can modify this message as needed.
  git commit -m "update content (from ${SERVER_NAME})"

  # Push the changes to the branch to sync
  git push origin ${SYNC_BRANCH}

  echo "Changes have been pushed successfully."
else
  echo "No changes detected. No action needed."
fi

# Clean up the cache directory
find "${REPO_DIR}/site/cache/" -type f ! -name 'index.html' -delete
find "${REPO_DIR}/site/cache/" -mindepth 1 -type d -delete
