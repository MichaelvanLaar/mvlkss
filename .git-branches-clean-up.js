const { execSync } = require("child_process");

function execCommand(command) {
  return execSync(command).toString().trim().split("\n");
}

// Fetch and prune
execCommand("git fetch --prune");

// Get the name of the currently checked out branch
const currentBranch = execCommand("git branch --show-current")[0];

// Get a list of merged branches, excluding the current branch and any lines with '*'
const branches = execCommand("git branch --merged main")
  .map((branch) => branch.trim()) // Trim whitespace
  .filter(
    (branch) => branch && branch !== currentBranch && !branch.includes("*"),
  );

// Delete each branch safely
branches.forEach((branch) => {
  if (branch !== "main" && branch !== "main") {
    // Replace with your main branch names if different
    try {
      execSync(`git branch -d ${branch}`, { stdio: "inherit" });
    } catch (error) {
      console.error(`Error deleting branch ${branch}:`, error.message);
    }
  }
});

console.log("Completed branch cleanup.");
