# Setting Up GitHub API Token for Version Management

The Alumni Tracking System uses the GitHub API to check for updates, allowing you to easily update to the latest version. To avoid rate limiting issues, you should configure a GitHub Personal Access Token.

## Why a GitHub Token is Needed

When using the GitHub API:

-   **Without a token:** Limited to 60 requests per hour
-   **With a token:** Increased to 5,000 requests per hour

This is particularly important when checking for updates frequently or when deploying to multiple institutions.

## Steps to Set Up a GitHub API Token

1. **Create a GitHub Account** (if you don't have one already)

    - Sign up at [GitHub.com](https://github.com)

2. **Generate a Personal Access Token (PAT)**

    - Go to [GitHub Settings > Developer Settings > Personal Access Tokens > Tokens (classic)](https://github.com/settings/tokens)
    - Click "Generate new token" > "Generate new token (classic)"
    - Give your token a descriptive name (e.g., "Alumni Tracking System Updates")
    - Set an expiration date (recommend at least 1 year)
    - Select **only** the following scopes:
        - `public_repo` (to access public repositories)
    - Click "Generate token" at the bottom
    - **IMPORTANT**: Copy the token immediately as you won't be able to see it again

3. **Configure the Token in Your Application**

    - Open your `.env` file in the root of your application
    - Add the following line:

    ```
    GITHUB_API_TOKEN="your_token_here"
    ```

    - Replace `your_token_here` with the token you copied from GitHub
    - Save the file

4. **Verify the Token is Working**
    - Restart your application if it's running
    - Go to the System Version Management page
    - You should see a green notification indicating "GitHub API Token Configured" with your rate limit information

## Security Considerations

-   The token you created has minimal permissions (only public repository access)
-   Store your `.env` file securely and don't commit it to version control
-   If you believe your token has been compromised, revoke it immediately on GitHub and create a new one

## Troubleshooting

If you're still experiencing GitHub API issues after setting up the token:

1. **Check your token is correctly formatted** in the `.env` file
2. **Verify the application has been restarted** after adding the token
3. **Check your GitHub account status** and verify the token is still active
4. **Try generating a new token** if issues persist

For additional assistance, please contact support.
