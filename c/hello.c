#include <stdio.h>
#include <string.h> // Include the string.h header for string manipulation functions

char* getLongestPalindrome(const char* s) {
    int n = strlen(s);
    int index = 0, palindromeLength = 1;
    for (int i = 1; i < n; i++) {
        int left = i - 1, right = i;
        while (left >= 0 && right < n && s[left] == s[right]) {
            if (right - left + 1 > palindromeLength) {
                index = left;
                palindromeLength = right - left + 1;
            }
            left--;
            right++;
        }
        left = i - 1;
        right = i + 1;
        while (left >= 0 && right < n && s[left] == s[right]) {
            if (right - left + 1 > palindromeLength) {
                index = left;
                palindromeLength = right - left + 1;
            }
            left--;
            right++;
        }
    }
    char* ans = malloc(palindromeLength + 1); // Allocate memory for the result
    strncpy(ans, s + index, palindromeLength); // Copy the palindrome substring
    ans[palindromeLength] = '\0'; // Null-terminate the result
    return ans;
}

int main() {
    const char* input = "babad";
    char* result = getLongestPalindrome(input);
    printf("Longest Palindrome: %s\n", result);
    free(result); // Don't forget to free the allocated memory
    return 0;
}

