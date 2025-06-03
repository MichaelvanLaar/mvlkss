/**
 * =============================================================================
 * Clean-Up Git Branches
 *
 * This script automates local Git branch cleanup. It retrieves updates from the
 * remote repository, excludes the current branch, and deletes branches merged
 * into `main`, except `main`.
 * =============================================================================
 */

const { execSync } = require("child_process");

function execCommand(command) {
  return execSync(command).toString().trim().split("\n");
}

// Fetch and prune
execCommand("git fetch --prune");

// Get the name of the currently checked out branch
const currentBranch = execCommand("git branch --show-current")[0];

// Get a list of merged branches, excluding the current branch and any lines
// with '*'
const branches = execCommand("git branch --merged main")
  .map((branch) => branch.trim())
  .filter(
    (branch) => branch && branch !== currentBranch && !branch.includes("*"),
  );

// Delete each branch safely
// Main branches that should never be removed
const protectedBranches = ["main", "master"];

branches.forEach((branch) => {
  // Replace with your main branch names if different
  if (!protectedBranches.includes(branch)) {
    try {
      execSync(`git branch -d ${branch}`, { stdio: "inherit" });
    } catch (error) {
      console.error(`Error deleting branch ${branch}:`, error.message);
    }
  }
});

console.log("Completed branch cleanup.");
